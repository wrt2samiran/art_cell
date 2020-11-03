<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UnitMaster;
use App\Models\{SparePart,SparePartCart,City,SparePartOrder,SparePartDeliveryAddress,OrderedSparePartDetail};
use Helper;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class SparePartOrderController extends Controller
{
    //defining the view path
    private $view_path='admin.order_spare_parts';
    //defining data array
    private $data=[];

    public function spare_parts_for_orders(Request $request){
    	$this->data['page_title']='Order Spare Parts';
        if($request->ajax()){

            $spareParts=SparePart::with('unitmaster')->orderBy('id','Desc');
            return Datatables::of($spareParts)
            ->addColumn('action',function($spare_part){
            	$action_url=route('admin.spare_parts_add_to_cart',$spare_part->id);
            	return '<form class="form-inline" action="'.$action_url.'"><input type="number" step="1" value="1" max="'.$spare_part->quantity_available.'" min="1" class="form-control mb-2 mr-sm-2" name="quantity" placeholder="Enter Quantity"><button type="submit" class="btn btn-primary mb-2">Add To Cart</button></form>';  
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view($this->view_path.'.spare_parts_for_order',$this->data);
    }


    public function add_to_cart($spare_part_id,Request $request){
        $spare_part=SparePart::findOrFail($spare_part_id);
        $current_user=auth()->guard('admin')->user();
        //checking sapre part status
        if($spare_part->is_active){
            //checking quantity is available or not
            if($request->quantity>$spare_part->quantity_available){
                return redirect()->back()->with('error','Quantity you added not available for this spare part.');
            }else{
                //check if the item already in cart 
                $spare_part_cart=SparePartCart::where('spare_part_id',$spare_part_id)
                                ->where('user_id',$current_user->id)
                                ->first();
                if($spare_part_cart){
                    return redirect()->back()->with('error','Spare part already in your cart.');
                }else{
                    SparePartCart::create([
                        'user_id'=>$current_user->id,
                        'spare_part_id'=>$spare_part_id,
                        'quantity'=>$request->quantity
                    ]);
                    return redirect()->back()->with('success','Spare part added to the cart.');
                }
            }

        }else{
            return redirect()->back()->with('error','Spare part is not active.');
        }

    }

    public function spare_parts_cart(){
    	$this->data['page_title']='Spare Parts Cart';
        $current_user=auth()->guard('admin')->user();
        $this->data['site_setting']=$site_setting=$this->site_setting();
        $spare_part_carts=SparePartCart::with('spare_part_details')
                        ->whereHas('spare_part_details')
                        ->where('user_id',$current_user->id)
                                ->get();


        $this->data['sub_total']=$sub_total=self::cart_total_without_tax($spare_part_carts);  

        $this->data['tax_percentage']=$tax_percentage=$site_setting['tax'];

        $this->data['tax_amount']=$tax_amount=(($tax_percentage/100)*$sub_total);

        $this->data['total']=$sub_total+$tax_amount;

        $this->data['spare_part_carts']=$spare_part_carts;                      
    	return view($this->view_path.'.cart',$this->data); 
    }

    public function update_cart($spare_part_id,Request $request){
        $spare_part=SparePart::findOrFail($spare_part_id);
        $current_user=auth()->guard('admin')->user();

        if($spare_part->is_active){
            //checking quantity is available or not
            if($request->quantity>$spare_part->quantity_available){
                return redirect()->back()->with('error','Quantity you added not available for this spare part.');
            }else{
                //check if the item already in cart 
                $spare_part_cart=SparePartCart::where('spare_part_id',$spare_part_id)
                                ->where('user_id',$current_user->id)
                                ->first();
                if($spare_part_cart->user_id!=$current_user->id){
                    abort(403,'Cart not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
                }
                $spare_part_cart->update([
                    'quantity'=>$request->quantity
                ]);
                return redirect()->back()->with('success','Cart updated.');
            }

        }else{
            return redirect()->back()->with('error','Spare part is not active.');
        }
    }

    public function delete_cart($spare_part_id){

        $current_user=auth()->guard('admin')->user();
        //check if the item already in cart 
        $spare_part_cart=SparePartCart::where('spare_part_id',$spare_part_id)
                        ->where('user_id',$current_user->id)
                        ->first();
        if($spare_part_cart){
            if($spare_part_cart->user_id!=$current_user->id){
                abort(403,'Cart not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }
            $spare_part_cart->delete();
        }
        return redirect()->back()->with('success','Spare part removed from cart'); 
    }

    public function spare_parts_checkout(){
        $this->data['page_title']='Spare Parts Checkout';
        $current_user=auth()->guard('admin')->user();

        $this->data['site_setting']=$site_setting=$this->site_setting();

        $cities=City::with(['state','country'])
        ->whereHas('state')
        ->whereHas('country')
        ->orderBy('id','Desc')->get();
        $this->data['cities']=$cities;
        $spare_part_carts=SparePartCart::with('spare_part_details')
                        ->whereHas('spare_part_details')
                        ->where('user_id',$current_user->id)
                                ->get();

        $this->data['sub_total']=$sub_total=self::cart_total_without_tax($spare_part_carts);  

        $this->data['tax_percentage']=$tax_percentage=$site_setting['tax'];

        $this->data['tax_amount']=$tax_amount=(($tax_percentage/100)*$sub_total);

        $this->data['total']=$sub_total+$tax_amount;


        $this->data['spare_part_carts']=$spare_part_carts;                      
        return view($this->view_path.'.checkout',$this->data); 

    }

    public function submit_order(Request $request){

        $current_user=auth()->guard('admin')->user();
        $site_setting=$this->site_setting();
        $spare_part_carts=SparePartCart::with('spare_part_details')
                        ->whereHas('spare_part_details')
                        ->where('user_id',$current_user->id)
                                ->get();

        //checking stock available for all spare parts
        if (count($spare_part_carts)){
           foreach ($spare_part_carts as $spare_part_cart) {
               if($spare_part_cart->quantity>$spare_part_cart->spare_part_details->quantity_available){
                    return redirect()->route('admin.spare_parts_cart')->with('checkout_error','Can not place the order due to unavailability of stock for some spare parts in your cart');
               }
           }
        }

        $sub_total=self::cart_total_without_tax($spare_part_carts);  

        $tax_percentage=$site_setting['tax'];

        $tax_amount=(($tax_percentage/100)*$sub_total);

        $total=$sub_total+$tax_amount;


        $order=SparePartOrder::create([
            'user_id'=>$current_user->id,
            'order_currency'=>Helper::getSiteCurrency(),
            'total_amount'=>$total,
            'tax_percentage'=>$tax_percentage,
            'tax_amount'=>$tax_amount,
            'delivery_charge'=>'0',
            'is_paid'=>true,
            'curent_status'=>'Placed',
            'updated_by'=>$current_user->id,
        ]);

        $order_items_array=[];
        foreach ($spare_part_carts as $spare_part_cart) {
            $order_items_array[]=[
                'spare_part_order_id'=>$order->id,
                'spare_part_id'=>$spare_part_cart->spare_part_id,
                'quantity'=>$spare_part_cart->quantity,
                'price'=>$spare_part_cart->spare_part_details->price,
                'total_price'=>($spare_part_cart->spare_part_details->price*$spare_part_cart->quantity)
            ];

            $quantity_available=($spare_part_cart->spare_part_details->quantity_available - $spare_part_cart->quantity);
            //update quantity available for the spare part
            SparePart::find($spare_part_cart->spare_part_id)->update([
                'quantity_available'=>($quantity_available>0)?$quantity_available:'0'
            ]);

        }

        OrderedSparePartDetail::insert($order_items_array);

        $city=City::find($request->city_id);
        SparePartDeliveryAddress::create([
            'order_id'=>$order->id,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'contact_number'=>$request->contact_number,
            'address_line_1'=>$request->address_line_1,
            'address_line_2'=>$request->address_line_2, 
            'pincode'=>$request->pincode,
            'city_id'=>$city->id,
            'state_id'=>$city->state_id,
            'country_id'=>$city->country_id
        ]);

        //clear cart
        SparePartCart::where('user_id',$current_user->id)->delete();
        return redirect()->route('admin.spare_parts_for_order')->with('success','Order successfully placed.');
    }


    public static function cart_total_without_tax($spare_part_carts){
        $sub_total_price_array=[];
        if(count($spare_part_carts)){
            foreach ($spare_part_carts as $cart) {

                $cart_total=($cart->quantity*$cart->spare_part_details->price);
                $cart->total=$cart_total;
                $sub_total_price_array[]=$cart_total;
            }
        }  

        return array_sum($sub_total_price_array);
    }


    public function spare_parts_ordered(Request $request){
        $this->data['page_title']='Ordered Spare Parts';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $spare_parts_ordered=SparePartOrder::where('user_id',$current_user->id)
                        ->withCount('ordered_spare_parts')
                        ->orderBy('id','Desc');
            return Datatables::of($spare_parts_ordered)
            ->addColumn('action',function($order){
                $details_ajax_url=route('admin.ajax_order_details',$order->id);
                return '<a class="btn btn-primary" href="javascript:order_details('."'".$details_ajax_url."'".')">Details</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view($this->view_path.'.spare_parts_ordered',$this->data);
    }


    public function ajax_order_details($order_id){
        $spare_part_order=SparePartOrder::with('ordered_spare_parts')->find($order_id);
        $this->data['order']=$spare_part_order;
        $view=view($this->view_path.'.ajax.order_details',$this->data)->render();
        return response()->json(['html'=>$view]);
    }



    

}
