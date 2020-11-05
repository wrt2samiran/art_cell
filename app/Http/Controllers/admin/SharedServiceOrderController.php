<?php
/*********************************************************/
# Class name     : SharedServiceOrderController           #
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
# Created Date   : 03-11-2020                             #
# Modified Date  : 04-11-2020                             #
# Purpose        : Shared Service Order Management        #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UnitMaster;
use App\Models\{SharedService,SharedServiceCart,City,SharedServiceOrder,SharedServiceDeliveryAddress,OrderedSharedServiceDetail};
use Helper;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Events\Order\SharedService\OrderPlaced;
class SharedServiceOrderController extends Controller
{
    //defining the view path
    private $view_path='admin.shared_service_orders';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function to display shared services for order                          #
    # Function name    : create_order                                        #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To display shared services for order                #

    public function create_order(Request $request){
    	$this->data['page_title']='Order Spare Parts';
        if($request->ajax()){

            $shared_services=SharedService::orderBy('id','Desc');
            return Datatables::of($shared_services)
            ->addColumn('action',function($shared_service){
            	$action_url=route('admin.shared_service_orders.add_to_cart',$shared_service->id);
            	return '<form  action="'.$action_url.'">
            	<div class="row">
	            	<div class="col-md-3">
	            	Quantity
	            	<input type="number" step="1" value="1" max="'.$shared_service->quantity_available.'" min="1" class="form-control" name="quantity" placeholder="Enter Quantity">
	            	</div>
	            	<div class="col-md-3">
	            	Extra Day <input type="number" step="1" value="0"  min="0" class="form-control" name="no_of_extra_days" placeholder="Extra Days">
	            	</div>
	            	<div class="col-md-6" style="padding-top:22px;">
	            	<button type="submit" class="btn btn-primary mb-2">Add To Cart</button>
	            	</div>
            	</div>

            	</form>';   
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view($this->view_path.'.create_order',$this->data);
    }

    /************************************************************************/
    # Function to store shared service to cart                               #
    # Function name    : add_to_cart                                         #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To store shared service to cart                     #
    # Param            : shared_service_id,Request $request                  #

    public function add_to_cart($shared_service_id,Request $request){
        $shared_service=SharedService::findOrFail($shared_service_id);
        $current_user=auth()->guard('admin')->user();
        //checking sapre part status
        if($shared_service->is_active){
            //checking quantity is available or not
            if($request->quantity>$shared_service->quantity_available){
                return redirect()->back()->with('error','Quantity you added not available for this shared service.');
            }else{
                //check if the item already in cart 
                $shared_service_cart=SharedServiceCart::where('shared_service_id',$shared_service_id)
                                ->where('user_id',$current_user->id)
                                ->first();
                if($shared_service_cart){
                    return redirect()->back()->with('error','Shared service already in your cart.');
                }else{
                    SharedServiceCart::create([
                        'user_id'=>$current_user->id,
                        'shared_service_id'=>$shared_service_id,
                        'quantity'=>$request->quantity,
                        'no_of_extra_days'=>$request->no_of_extra_days
                    ]);
                    return redirect()->back()->with('success','Shared service added to the cart.');
                }
            }

        }else{
            return redirect()->back()->with('error','Shared service is not active.');
        }

    }

    /************************************************************************/
    # Function to display shared service cart data                           #
    # Function name    : cart                                                #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To display shared service cart data                 #

    public function cart(){
    	$this->data['page_title']='Shared Service Cart';
        $current_user=auth()->guard('admin')->user();
        $this->data['site_setting']=$site_setting=$this->site_setting();

        $shared_service_carts=SharedServiceCart::with('shared_service_details')
                        ->whereHas('shared_service_details')
                        ->where('user_id',$current_user->id)
                                ->get();

        if(count($shared_service_carts)){
            foreach ($shared_service_carts as $cart) {
                $total_unit_price=$cart->shared_service_details->price+($cart->no_of_extra_days*$cart->shared_service_details->extra_price_per_day);

                $cart_total=($cart->quantity*$total_unit_price);

                $cart->total=$cart_total;
                $sub_total_price_array[]=$cart_total;
            }
        } 
        $this->data['sub_total']=$sub_total=self::cart_total_without_tax($shared_service_carts);  
        $this->data['tax_percentage']=$tax_percentage=$site_setting['tax'];

        $this->data['tax_amount']=$tax_amount=(($tax_percentage/100)*$sub_total);
        $this->data['total']=$sub_total+$tax_amount;
        $this->data['shared_service_carts']=$shared_service_carts;                      
    	return view($this->view_path.'.cart',$this->data); 
    }

    /************************************************************************/
    # Function to update shared service cart by cart id                      #
    # Function name    : update_cart                                         #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To update shared service cart by cart id            #
    # Param            : cart_id,Request $request                            #
    public function update_cart($cart_id,Request $request){

    	$shared_service_cart=SharedServiceCart::findOrFail($cart_id);
 
        $shared_service=SharedService::findOrFail($shared_service_cart->shared_service_id);
        $current_user=auth()->guard('admin')->user();

        if($shared_service->is_active){
            //checking quantity is available or not
            if($request->quantity>$shared_service->quantity_available){
                return redirect()->back()->with('error','Quantity you added not available for this shareed service.');
            }else{

                if($shared_service_cart->user_id!=$current_user->id){
                    abort(403,'Cart not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
                }
                $shared_service_cart->update([
                    'quantity'=>$request->quantity,
                    'no_of_extra_days'=>$request->no_of_extra_days
                ]);
                return redirect()->back()->with('success','Cart updated.');
            }

        }else{
            return redirect()->back()->with('error','Shared service is not active.');
        }
    }

    /************************************************************************/
    # Function to delete shared service to cart                              #
    # Function name    : delete_cart                                         #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To delete shared service to cart                    #
    # Param            : cart_id                                             #
    public function delete_cart($cart_id){

        $current_user=auth()->guard('admin')->user();
        //check if the item already in cart 
        $shared_service_cart=SharedServiceCart::findOrFail($cart_id);

        if($shared_service_cart){
            if($shared_service_cart->user_id!=$current_user->id){
                abort(403,'Cart not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }
            $shared_service_cart->delete();
        }
        return redirect()->back()->with('success','Shared service removed from cart'); 
    }

    /************************************************************************/
    # Function to load shared service checkout page                          #
    # Function name    : checkout                                            #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To load shared service checkout page                #

    public function checkout(){
        $this->data['page_title']='Shared Services Checkout';
        $current_user=auth()->guard('admin')->user();

        $this->data['site_setting']=$site_setting=$this->site_setting();

        $cities=City::with(['state','country'])
        ->whereHas('state')
        ->whereHas('country')
        ->orderBy('id','Desc')->get();
        $this->data['cities']=$cities;
        $shared_service_carts=SharedServiceCart::with('shared_service_details')
                        ->whereHas('shared_service_details')
                        ->where('user_id',$current_user->id)
                                ->get();


        if(count($shared_service_carts)){
            foreach ($shared_service_carts as $cart) {
                $total_unit_price=$cart->shared_service_details->price+($cart->no_of_extra_days*$cart->shared_service_details->extra_price_per_day);

                $cart_total=($cart->quantity*$total_unit_price);

                $cart->total=$cart_total;
                $sub_total_price_array[]=$cart_total;
            }
        } 

        $this->data['sub_total']=$sub_total=self::cart_total_without_tax($shared_service_carts);  

        $this->data['tax_percentage']=$tax_percentage=$site_setting['tax'];

        $this->data['tax_amount']=$tax_amount=(($tax_percentage/100)*$sub_total);

        $this->data['total']=$sub_total+$tax_amount;


        $this->data['shared_service_carts']=$shared_service_carts;                      
        return view($this->view_path.'.checkout',$this->data); 

    }

    /************************************************************************/
    # Function to submit shared service order                                #
    # Function name    : submit_order                                        #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To submit shared service order                      #
    # Param            : Request $request                                    #
    public function submit_order(Request $request){

        $current_user=auth()->guard('admin')->user();
        $site_setting=$this->site_setting();
        $shared_service_carts=SharedServiceCart::with('shared_service_details')
                        ->whereHas('shared_service_details')
                        ->where('user_id',$current_user->id)
                                ->get();

        //checking stock available for all spare parts
        if (count($shared_service_carts)){

           foreach ($shared_service_carts as $shared_service_cart) {
               if($shared_service_cart->quantity>$shared_service_cart->shared_service_details->quantity_available){
                    return redirect()->route('admin.shared_service_orders.cart')->with('error','Can not place the order due to unavailability of stock for some shared services in your cart');
               }
           }
        }

        $sub_total=self::cart_total_without_tax($shared_service_carts);  

        $tax_percentage=$site_setting['tax'];

        $tax_amount=(($tax_percentage/100)*$sub_total);

        $total=$sub_total+$tax_amount;


        $order=SharedServiceOrder::create([
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
        foreach ($shared_service_carts as $shared_service_cart) {

            $shared_service=$shared_service_cart->shared_service_details;
            $total_days=$shared_service->number_of_days+ $shared_service_cart->no_of_extra_days;

            $total_unit_price=$shared_service->price+($shared_service_cart->no_of_extra_days*$shared_service->extra_price_per_day);

            $order_items_array[]=[
                'order_id'=>$order->id,
                'shared_service_id'=>$shared_service_cart->shared_service_id,
                'no_of_days'=>$shared_service->number_of_days,
                'quantity'=>$shared_service_cart->quantity,
                'price'=>$shared_service->price,
                'no_of_extra_days'=>$shared_service_cart->no_of_extra_days,
                'extra_days_price'=>$shared_service->extra_price_per_day,
                'total_days'=>$total_days,
                'total_unit_price'=>$total_unit_price,
                'total_price'=>($total_unit_price*$shared_service_cart->quantity)
            ];

            $quantity_available=($shared_service_cart->shared_service_details->quantity_available - $shared_service_cart->quantity);
            //update quantity available for the spare part
            SharedService::find($shared_service_cart->shared_service_id)->update([
                'quantity_available'=>($quantity_available>0)?$quantity_available:'0'
            ]);

        }

        OrderedSharedServiceDetail::insert($order_items_array);

        $city=City::find($request->city_id);
        SharedServiceDeliveryAddress::create([
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
        SharedServiceCart::where('user_id',$current_user->id)->delete();

        $order_details=SharedServiceOrder::with(['ordered_shared_services','user','delivery_address'])->where('id',$order->id)->first();

        event(new OrderPlaced($order_details));

        return redirect()->route('admin.shared_service_orders.my_orders')->with('success','Order successfully placed.');
    }

    /************************************************************************/
    # Function to return cart total price without tax                        #
    # Function name    : cart_total_without_tax                              #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To return cart total price without tax              #
    # Param            : $shared_service_carts (Collection)                  #

    public static function cart_total_without_tax($shared_service_carts){
        $sub_total_price_array=[];
        if(count($shared_service_carts)){
            foreach ($shared_service_carts as $cart) {
                $total_unit_price=$cart->shared_service_details->price+($cart->no_of_extra_days*$cart->shared_service_details->extra_price_per_day);

                $cart_total=($cart->quantity*$total_unit_price);

                $sub_total_price_array[]=$cart_total;
            }
        }  
        return array_sum($sub_total_price_array);
    }

    /************************************************************************/
    # Function to display my shared service orders                           #
    # Function name    : my_orders                                           #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To display my shared service orders                 #
    # Param            : Request $request                                    #

    public function my_orders(Request $request){
        $this->data['page_title']='Shared Services- My Orders';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $shared_service_ordered=SharedServiceOrder::where('user_id',$current_user->id)
                        ->withCount('ordered_shared_services')
                        ->orderBy('id','Desc');
            return Datatables::of($shared_service_ordered)
            ->addColumn('action',function($order){

                $details_ajax_url=route('admin.shared_service_orders.ajax_my_order_details',$order->id);
                return '<a class="btn btn-success" href="javascript:order_details('."'".$details_ajax_url."'".')">Details</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view($this->view_path.'.my_orders',$this->data);
    }

    /************************************************************************/
    # Function to return my order details html ajax response                 #
    # Function name    : ajax_my_order_details                               #
    # Created Date     : 03-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To return my order details html ajax response       #
    # Param            : order_id                                            #

    public function ajax_my_order_details($order_id){
        $shared_service_order=SharedServiceOrder::with('ordered_shared_services')->find($order_id);
        $this->data['order']=$shared_service_order;
        $view=view($this->view_path.'.ajax.order_details',$this->data)->render();
        return response()->json(['html'=>$view]);
    }

    /************************************************************************/
    # Function to load all shared service orders for admin                   #
    # Function name    : order_list                                          #
    # Created Date     : 04-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To load all shared service orders for admin         #
    # Param            : Request $request                                    #

    public function order_list(Request $request){
        $this->data['page_title']='Shared Service Orders';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $shared_services_ordered=SharedServiceOrder::withCount('ordered_shared_services')
                        ->with('user')
                        ->orderBy('id','Desc');
                        
            return Datatables::of($shared_services_ordered)
            ->addColumn('action',function($order){
                return '<a class="btn btn-success" href="'.route('admin.shared_service_orders.order_details',$order->id).'">Details</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view($this->view_path.'.manage.order_list',$this->data);
    }

    /************************************************************************/
    # Function to load shared service order details for admin                #
    # Function name    : order_list                                          #
    # Created Date     : 04-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To load shared service order details for admin      #
    # Param            : order_id                                            #
    public function order_details($order_id){
        $this->data['page_title']='Shared Service Order Details';
        $spare_part_order=SharedServiceOrder::with('ordered_shared_services','user')->find($order_id);
        $this->data['order']=$spare_part_order;
        return view($this->view_path.'.manage.order_details',$this->data);
    }

    /************************************************************************/
    # Function to update order status                                        #
    # Function name    : update_order_status                                 #
    # Created Date     : 04-11-2020                                          #
    # Modified date    : 04-11-2020                                          #
    # Purpose          : To update order status                              #
    # Param            : order_id, Request $request                          #

    public function update_order_status($order_id,Request $request){
        $spare_part_order=SharedServiceOrder::findOrFail($order_id);
        $spare_part_order->update([
            'curent_status'=>$request->status,
            'updated_by'=>auth()->guard('admin')->id()
        ]);
        return redirect()->back()->with('success','Order status successfully updated.');
    }

    

}
