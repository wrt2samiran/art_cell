<?php
namespace App\Http\Helpers;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\{Message,Notification,Setting,EmailTemplate};

class Helper
{
    



    /*****************************************************/
    # Helper
    # Function name : generateUniqueSlug
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function generateUniqueSlug($model, $slug, $id = null)
    {
        $slug = str_slug($slug);
        $currentSlug = '';
        if ($id) {
            $currentSlug = $model->where('id', '=', $id)->value('slug');
        }

        if ($currentSlug && $currentSlug === $slug) {
            return $slug;
        } else {
            $slugList = $model->where('slug', 'LIKE', $slug . '%')->pluck('slug');
            if ($slugList->count() > 0) {
                $slugList = $slugList->toArray();
                if (!in_array($slug, $slugList)) {
                    return $slug;
                }
                $newSlug = '';
                for ($i = 1; $i <= count($slugList); $i++) {
                    $newSlug = $slug . '-' . $i;
                    if (!in_array($newSlug, $slugList)) {
                        return $newSlug;
                    }
                }
                return $newSlug;
            } else {
                return $slug;
            }
        }
    }

    /*****************************************************/
    # Helper
    # Function name : getSiteSettings
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Generate unique slug for product, category and so on
    # Params        :
    /*****************************************************/
    public static function getSiteSettings()
    {
        $siteSettingData = SiteSetting::first();
        return $siteSettingData;
    }

    /*****************************************************/
    # Helper
    # Function name : generateOtp
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function generateOtp()
    {
        $string = '0123456789';
        $string_shuffled = str_shuffle($string);
        $OTP = substr($string_shuffled, 1, 6);
        return $OTP;
    }

    /*****************************************************/
    # Helper
    # Function name : getBaseUrl
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getBaseUrl()
    {
        $baseUrl = url('/');
        return $baseUrl;
    }


    /*****************************************************/
    # Helper
    # Function name : formattedDate
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedDate($getDate = null)
    {
        $formattedDate = date('dS M, Y');
        if ($getDate != null) {
            $formattedDate = date('dS M, Y', strtotime($getDate));
        }
        return $formattedDate;
    }

    /*****************************************************/
    # Helper
    # Function name : formattedDateTime
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedDateTime($getDateTime = null)
    {
        $formattedDateTime = '';
        if ($getDateTime != null) {
            $formattedDateTime = date('dS M, Y H:i', $getDateTime);
        }
        return $formattedDateTime;
    }

    /*****************************************************/
    # Helper
    # Function name : formattedDatefromTimestamp
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedDatefromTimestamp($getDateTime = null)
    {
        $formattedDateTime = '';
        if ($getDateTime != null) {
            $formattedDateTime = date('dS M, Y', $getDateTime);
        }
        return $formattedDateTime;
    }

    /*****************************************************/
    # Helper
    # Function name : formattedTimestamp
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedTimestamp($getDateTime = null)
    {
        $timestamp = '';
        if ($getDateTime != null) {
            $timestamp = \Carbon\Carbon::createFromFormat('m/d/Y', $getDateTime)->timestamp;
        }
        return $timestamp;
    }
    
    /*****************************************************/
    # Helper
    # Function name : differnceBtnTimestampDateFrmCurrentDateInDays
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function differnceBtnTimestampDateFrmCurrentDateInDays($getDate = null)
    {
        $days = '';
        if ($getDate != null) {
            $currentDate = date('Y-m-d');
            $diff   = abs($getDate - strtotime($currentDate));
            $years  = floor($diff / (365*60*60*24)); 
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
            $days   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if ($getDate < strtotime($currentDate)) {
                $days = '-'.$days;
            } else {
                $days = '+'.$days;
            }

        }
        return $days;
    }

    /*****************************************************/
    # Helper
    # Function name : getMetaData
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getMetaData($table = 'SiteSetting', $where = '')
    {
        if ($table == 'cms') {
            $metaData = Cms::select('name', 'meta_keyword', 'meta_description')->where('slug', $where)->first();
            $return['title'] = $metaData['name'];
            $return['keyword'] = $metaData['meta_keyword'];
            $return['description'] = $metaData['meta_description'];
            return $return;
        } else {
            $metaData = SiteSetting::select('default_meta_title', 'default_meta_keywords', 'default_meta_description')->first();
            $return['title'] = $metaData['default_meta_title'];
            $return['keyword'] = $metaData['default_meta_keywords'];
            $return['description'] = $metaData['default_meta_description'];
            return $return;
        }
    }

    /*****************************************************/
    # Helper
    # Function name : customEncryptionDecryption
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function customEncryptionDecryption($string, $action = 'encrypt')
    {
        $secretKey = 'c7tpe291z';
        $secretVal = 'GfY7r512';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretVal), 0, 16);

        if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    
    /*****************************************************/
    # Helper
    # Function name : cleanString
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/        
    public static function cleanString($content) {
        $content = preg_replace("/&#?[a-z0-9]+;/i","",$content); 
        $content = preg_replace("/[\n\r]/","",$content);
        $content = strip_tags($content);
        return $content;
    }

    /*****************************************************/
    # Helper
    # Function name : cleanString
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/ 
    public static function complete_percentage($model, $table_name, $user_id){
        $pos_info =  DB::select(DB::raw('SHOW COLUMNS FROM '.$table_name));
        $base_columns = count($pos_info);
        $not_null = 0;
        foreach ($pos_info as $col){
            $not_null += app('App\\'.$model)::selectRaw('SUM(CASE WHEN '.$col->Field.' IS NOT NULL THEN 1 ELSE 0 END) AS not_null')->where('user_id', '=', $user_id)->first()->not_null;
        }
        $alter = $base_columns - 6;
        $value = $not_null / $alter *100;
        $value = round($value, 2);
        return $value;
    }


    
    /*****************************************************/
    # RangeController
    # Function name : Random String
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Generate Random Password
    # Params        : Request $length
    /*****************************************************/

    public static function rand_string($length) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);

    }

    /*****************************************************/
    # Function name : ordinal                             #
    # Created Date  : 24-09-2020                          #
    # Purpose       : Generate ordinal suffix of a number #
    # Params        : $number
    /*****************************************************/
    public static function Ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    /*****************************************************/
    # Function name : get_site_setting                    #
    # Created Date  : 05-11-2020                          #
    # Purpose       : Get site setting                    #
    /*****************************************************/
    public static function get_site_setting()
    {
        return Setting::pluck('value','slug')->toArray();
    }

    /*****************************************************/
    # Function name : getSiteCurrency                     #
    # Created Date  : 28-10-2020                          #
    # Purpose       : Get site currency                   #
    /*****************************************************/
    public static function getSiteCurrency(){
        return 'SAR';
    }
    /*****************************************************/
    # Function name : get_file_type_by_mime_type          #
    # Created Date  : 05-11-2020                          #
    # Purpose       : Get file type by mimetype           #
    /*****************************************************/
    public static function get_file_type_by_mime_type($mime_type){
        if(in_array($mime_type,['image/jpeg','image/png','image/jpg'])){
            $file_type='image';
        }elseif (in_array($mime_type,['application/pdf'])) {
            $file_type='pdf';
        }elseif (in_array($mime_type,['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword'])) {
            $file_type='doc';
        }elseif(in_array($mime_type,['text/plain'])){
             $file_type='text';
        }else{
             $file_type='file';
        }
        return $file_type;
    }

    /*****************************************************/
    # Function name : get_admin_contact_mail              #
    # Created Date  : 05-11-2020                          #
    # Purpose       : Get admin contact email             #
    /*****************************************************/
    public static function get_admin_contact_mail(){
        if (array_key_exists("contact_email",self::get_site_setting())){
            return self::get_site_setting()['contact_email'];
        }else{
            return '';
        }
        
    }

    /*****************************************************/
    # Function name : get_unread_message_count            #
    # Created Date  : 02-12-2020                          #
    # Purpose       : Get user's unread message count     #
    /*****************************************************/
    public static function get_unread_message_count(){
        $current_user=auth()->guard('admin')->user();
        return Message::where('message_to',$current_user->id)
        ->where('is_read',false)
        ->count();
    }

    public static function emailTemplateMail($slug,array $variable_value){
        $email_template = EmailTemplate::where('slug',$slug)->first();
        $template_variables = explode(',' , $email_template->variable_name);
        $content =$email_template->content;
        foreach ($variable_value as $key=>$val){
            $content = str_replace($key, $val, $content);
        }
        
        // dd($email_template);
        return $content;

    }

    public static function notifications_count(){
        $current_user=auth()->guard('admin')->user();
        return Notification::where('user_id',$current_user->id)->where('is_read',false)->count();
    }

    public static function latest_three_notifications(){
        $current_user=auth()->guard('admin')->user();
        return Notification::where('user_id',$current_user->id)->take(3)->orderBy('id','desc')->get();
    }

    public static function contract_max_filesize($mb_or_kb){
        $file_setting=Setting::where('slug','contract-and-property-max-filesize')->first();

        if($file_setting){
            return ($mb_or_kb=='mb')?$file_setting->value:$file_setting->value*1024;
        }else{
           return ($mb_or_kb=='mb')?1:1024;
        }

    }


    public static function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $image_info = getimagesize($file);
        $image_type = $image_info[2];
          if( $image_type == IMAGETYPE_JPEG ) {
     
             $src = imagecreatefromjpeg($file);
          } elseif( $image_type == IMAGETYPE_GIF ) {
     
             $src = imagecreatefromgif($file);
          } elseif( $image_type == IMAGETYPE_PNG ) {
     
             $src = imagecreatefrompng($file);
          }

        //$src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }




}
