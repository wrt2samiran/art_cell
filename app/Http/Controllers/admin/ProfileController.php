<?php
/*********************************************************/
# Class name     : ProfileController                      #
# Methods  :                                              #
#    1. edit_profile ,                                    #
#    2. update_profile,                                   #
#    3. change_password                                   #
#    4. update_password                                   #
# Created Date   : 14-10-2020                             #
# Purpose        : Profile management                     #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Profile\{UpdatePasswordRequest,UpdateProfileRequest};
use Hash,File;
class ProfileController extends Controller
{

    //defining the view path
    private $view_path='admin.profile';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function to load edit profile view page                                #
    # Function name    : edit_profile                                        #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : To load edit profile view page                      #	
    public function edit_profile(){
        $this->data['page_title'] = "Edit Profile";
        return view($this->view_path.'.edit_profile',$this->data);
    }

    /************************************************************************************/
    # Function to update profile                                                         #
    # Function name    : update_profile                                                  #
    # Created Date     : 14-10-2020                                                      #
    # Modified date    : 14-10-2020                                                      #
    # Purpose          : to update profile data of logged in user                        #
    # Param            : UpdateProfileRequest $request                           		 #
    public function update_profile(UpdateProfileRequest $request){

    	$current_user=auth()->guard('admin')->user();

    	if($request->hasFile('profile_image')){

    		//if user has profile image uploaded then removing the old one
    		if($current_user->profile_pic){
                //storing old path of the image in a variable
                $old_path=public_path().'/uploads/profile_images/'.$current_user->profile_pic;
                 //if file exists then deleting the file from folder
                if(File::exists($old_path)){
                  File::delete($old_path);
                }
            }

            $image = $request->file('profile_image');
            //storing image name in a variable
            $image_name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/profile_images');
            $image->move($destinationPath, $image_name);

        }else{
        	$image_name=$current_user->profile_pic;
        }

        $current_user->update([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'profile_pic'=>$image_name,
            'updated_by'=>auth()->guard('admin')->id()
        ]);
        
        return redirect()->back()->with('success','Profile successfully updated.');

    }

    /************************************************************************/
    # Function to load change password view page                             #
    # Function name    : change_password                                     #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : To load change password view page                   #	
    public function change_password(){
        $this->data['page_title'] = "Change Password";
        return view($this->view_path.'.change_password',$this->data);
    }

    /************************************************************************************/
    # Function to update password                                                        #
    # Function name    : update_password                                                 #
    # Created Date     : 14-10-2020                                                      #
    # Modified date    : 14-10-2020                                                      #
    # Purpose          : to update password of logged in user                            #
    # Param            : UpdatePasswordRequest $request                        			 #
    public function update_password(UpdatePasswordRequest $request){

    	$current_user=auth()->guard('admin')->user();
        if (!(Hash::check($request->current_password, $current_user->password))) {
            return redirect()->back()->with("error", "Current password does not matched.");
        } else {
        	$current_user->update([
        		'password'=>$request->new_password
        	]);
        	return redirect()->back()->with("success", "Password successfully changed");
        }

    }
}
