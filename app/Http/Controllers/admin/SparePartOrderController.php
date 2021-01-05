<?php
/*********************************************************/
# Class name     : SparePartOrderController               #
# Methods  :                                              #
#    1. create_order                                      #
#    2. add_to_cart                                       #
#    3. cart                                              #
#    4. update_cart                                       #
#    5. delete_cart                                       #
#    6. checkout                                          #
#    7. submit_order                                      #
#    8. cart_total_without_tax                            #
#    9. my_orders                                         #
#    10.ajax_my_order_details                             #
#    11.order_list                                        #
#    12.order_details                                     #
#    13.update_order_status                               #
#    14.download_invoice                                  #
# Created Date   : 02-11-2020                             #
# Modified Date  : 19-11-2020                             #
# Purpose        : Spare Part Order Management            #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UnitMaster;
use App\Models\{SparePart,SparePartCart,City,SparePartOrder,SparePartDeliveryAddress,OrderedSparePartDetail,Notification,Status};
use Helper;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Events\Order\SparePart\OrderPlaced;
use PDF;
class SparePartOrderController extends Controller
{
    //defining the view path
    private $view_path='admin.spare_part_orders';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function to display spare parts for order                              #
    # Function name    : create_order                                        #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To display spare parts for order                    #

    public function create_order(Request $request){
    	$this->data['page_title']='Order Spare Parts';
        if($request->ajax()){

            $spareParts=SparePart::with('unitmaster')->orderBy('id','Desc');
            return Datatables::of($spareParts)
            ->addColumn('image',function($spare_part){
                if(count($spare_part->images)){
                    $image_container='<div>';
                    foreach ($spare_part->images as $key => $image) {
                        $display=($key=='0')?'block':'none';

                        $image_container.='<a style="display:'.$display.'" href="'.asset('/uploads/spare_part_images/'.$image->image_name).'" 
                         data-fancybox="images-preview-'.$spare_part->id.'" 
                         data-width="1000" data-height="700"
                         >
                        <img style="height:60px;width:80px" src="'.asset('/uploads/spare_part_images/thumb/'.$image->image_name).'" />
                        </a>';
                    }
                    $image_container.='</div>';
                    return $image_container;
                }else{
                    return '<div>
                    <img style="height:60px;width:80px" src="'.asset('/uploads/spare_part_images/no_image.png').'"/>
                    </div>';
                }
            })
            ->addColumn('action',function($spare_part){
            	$action_url=route('admin.spare_part_orders.add_to_cart',$spare_part->id);
            	return '<form action="'.$action_url.'">
                    <div class="row">
                    <div class="col-md-6">
                    <input type="number" step="1" value="1"  min="1" class="form-control mb-2 mr-sm-2" name="quantity" placeholder="Enter Quantity">
                    </div>
                    <div class="col-md-6">
                    <button type="submit" class="btn btn-primary mb-2">Add To Cart</button>
                    </div>
                    </div>
                    </form>';  
            })
            ->rawColumns(['action','image'])
            ->make(true);
        }
        return view($this->view_path.'.create_order',$this->data);
    }

    /************************************************************************/
    # Function to store spare part to cart                                   #
    # Function name    : add_to_cart                                         #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To store spare part to cart                         #
    # Param            : spare_part_id,Request $request                      #
    public function add_to_cart($spare_part_id,Request $request){
        $spare_part=SparePart::findOrFail($spare_part_id);
        $current_user=auth()->guard('admin')->user();
        //checking sapre part status
        if($spare_part->is_active){

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

        }else{
            return redirect()->back()->with('error','Spare part is not active.');
        }

    }

    /************************************************************************/
    # Function to display spare part cart data                               #
    # Function name    : cart                                                #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To display spare part cart data                     #

    public function cart(){
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

    /************************************************************************/
    # Function to update spare part cart by cart id                          #
    # Function name    : update_cart                                         #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To update spare part cart by cart id                #
    # Param            : cart_id,Request $request                            #

    public function update_cart($cart_id,Request $request){

        $spare_part_cart=SparePartCart::findOrFail($cart_id);
        $spare_part=SparePart::findOrFail($spare_part_cart->spare_part_id);
        $current_user=auth()->guard('admin')->user();

        if($spare_part->is_active){
   
            if($spare_part_cart->user_id!=$current_user->id){
                abort(403,'Cart not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }
            $spare_part_cart->update([
                'quantity'=>$request->quantity
            ]);
            return redirect()->back()->with('success','Cart updated.');

        }else{
            return redirect()->back()->with('error','Spare part is not active.');
        }
    }

    /************************************************************************/
    # Function to delete spare part cart                                     #
    # Function name    : delete_cart                                         #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To delete spare part cart                           #
    # Param            : cart_id                                             #
    public function delete_cart($cart_id){

        $current_user=auth()->guard('admin')->user();
        //check if the item already in cart 
        $spare_part_cart=SparePartCart::findOrFail($cart_id);

        if($spare_part_cart){
            if($spare_part_cart->user_id!=$current_user->id){
                abort(403,'Cart not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }
            $spare_part_cart->delete();
        }
        return redirect()->back()->with('success','Spare part removed from cart'); 
    }

    /************************************************************************/
    # Function to load spare part checkout page                              #
    # Function name    : checkout                                            #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To load spare part checkout page                    #

    public function checkout(){
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

    /************************************************************************/
    # Function to submit spare part order                                    #
    # Function name    : submit_order                                        #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To submit spare part order                          #
    # Param            : Request $request                                    #
    public function submit_order(Request $request){

        $current_user=auth()->guard('admin')->user();
        $site_setting=$this->site_setting();
        $spare_part_carts=SparePartCart::with('spare_part_details')
                        ->whereHas('spare_part_details')
                        ->where('user_id',$current_user->id)
                                ->get();

        $default_status=Status::where('status_for','order')
        ->where('is_default_status',true)
        ->first();

        if(!$default_status){
            return redirect()->back()->with('error','No default status found for order');
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
            'status_id'=>$default_status->id,
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

        $order_details=SparePartOrder::with(['ordered_spare_parts','user','delivery_address'])->where('id',$order->id)->first();

        event(new OrderPlaced($order_details));
        return redirect()->route('admin.spare_part_orders.my_orders')->with('success','Order successfully placed.');
    }

    /************************************************************************/
    # Function to return cart total price without tax                        #
    # Function name    : cart_total_without_tax                              #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To return cart total price without tax              #
    # Param            : $spare_part_carts (Collection)                      #
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

    /************************************************************************/
    # Function to display my spare part orders                               #
    # Function name    : my_orders                                           #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To display my spare part orders                     #
    # Param            : Request $request                                    #

    public function my_orders(Request $request){

        $this->data['page_title']='Ordered Spare Parts';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $spare_parts_ordered=SparePartOrder::where('user_id',$current_user->id)
                        ->withCount('ordered_spare_parts')
                        ->with('status')
                        ->whereHas('status')
                        ->orderBy('id','Desc');
            return Datatables::of($spare_parts_ordered)
            ->addColumn('action',function($order){
                $details_ajax_url=route('admin.spare_part_orders.ajax_my_order_details',$order->id);
                return '<a class="btn btn-success" href="javascript:order_details('."'".$details_ajax_url."'".')">Details</a>';
            })
            ->editColumn('status.status_name', function ($order) {
                return '<span style="color:'.$order->status->color_code.'">'.$order->status->status_name.'<span>';
            })
            ->rawColumns(['action','status.status_name'])
            ->make(true);
        }
        return view($this->view_path.'.my_orders',$this->data);
    }

    /************************************************************************/
    # Function to return my order details html ajax response                 #
    # Function name    : ajax_my_order_details                               #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To return my order details html ajax response       #
    # Param            : order_id                                            #

    public function ajax_my_order_details($order_id){
        $spare_part_order=SparePartOrder::with('ordered_spare_parts')->find($order_id);
        $this->data['order']=$spare_part_order;
        $view=view($this->view_path.'.ajax.order_details',$this->data)->render();
        return response()->json(['html'=>$view]);
    }

    /************************************************************************/
    # Function to load all spare part orders for admin                       #
    # Function name    : order_list                                          #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To load all spare part orders for admin             #
    # Param            : Request $request                                    #

    public function order_list(Request $request){
        $this->data['page_title']='Spare Part Orders';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $spare_parts_ordered=SparePartOrder::withCount('ordered_spare_parts')
                        ->with('user')
                        ->with('status')
                        ->whereHas('status')
                        ->orderBy('id','Desc');
            return Datatables::of($spare_parts_ordered)
            ->addColumn('action',function($order){
                
                return '<a class="btn btn-success" href="'.route('admin.spare_part_orders.order_details',$order->id).'">Details</a>';
            })
            ->editColumn('status.status_name', function ($order) {
                return '<span style="color:'.$order->status->color_code.'">'.$order->status->status_name.'<span>';
            })
            ->rawColumns(['action','status.status_name'])
            ->make(true);
        }
        return view($this->view_path.'.manage.order_list',$this->data);
    }

    /************************************************************************/
    # Function to load spare part order details for admin                    #
    # Function name    : order_list                                          #
    # Created Date     : 04-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To load spare part order details for admin          #
    # Param            : order_id                                            #
    public function order_details($order_id){
        $this->data['page_title']='Spare Part Order Details';
        $spare_part_order=SparePartOrder::with('ordered_spare_parts','user')->find($order_id);
        $this->data['order']=$spare_part_order;
        $this->data['statuses']=Status::where('status_for','order')->whereIsActive(true)->get();
        return view($this->view_path.'.manage.order_details',$this->data);
    }

    /************************************************************************/
    # Function to update order status                                        #
    # Function name    : update_order_status                                 #
    # Created Date     : 02-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To update order status                              #
    # Param            : order_id, Request $request                          #

    public function update_order_status($order_id,Request $request){
        $spare_part_order=SparePartOrder::findOrFail($order_id);
        $spare_part_order->update([
            'status_id'=>$request->status,
            'updated_by'=>auth()->guard('admin')->id()
        ]);

        $notification_message=env('APP_NAME','SITE').' admin updated your order status';
        $redirect_path=route('admin.spare_part_orders.my_orders',['order_id'=>$spare_part_order->id],false);
        
        Notification::create([
            'notificable_id'=>$spare_part_order->id,
            'notificable_type'=>'App\Models\SparePartOrder',
            'user_id'=>$spare_part_order->user_id,
            'message'=>$notification_message,
            'redirect_path'=>$redirect_path,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);


        return redirect()->back()->with('success','Order status successfully updated.');
    }

    /************************************************************************/
    # Function to download invoice                                           #
    # Function name    : download_invoice                                 #
    # Created Date     : 19-11-2020                                          #
    # Modified date    : 19-11-2020                                          #
    # Purpose          : To download invoice                                 #
    # Param            : order_id, Request $request                          #

    public function download_invoice($order_id,Request $request){

        $spare_part_order=SparePartOrder::with('ordered_spare_parts','user')->find($order_id);
        $this->data['order']=$spare_part_order;
        $pdf = PDF::loadView($this->view_path.'.pdf.invoice', $this->data);
        
        $file_name='Invoice-for-spare-part-order-no-'.$spare_part_order->id.'.pdf';
        
        ob_end_clean(); //without ob_end_clean I got error before loade PDF after download. Got this solution from github
        return $pdf->download($file_name);
        //return redirect()->back()->with('success','Order status successfully updated.');
    }    

}
