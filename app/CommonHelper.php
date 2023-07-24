<?php 

namespace App\Helpers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;
use Image;

class CommonHelper{

    public static function getUserIp(){
        return request()->ip();
    }

    public static function getUTCDateTime($dateTime){

        $dateTime = date("Y-m-d H:i:s",strtotime($dateTime)); 
        $newDateTime = new DateTime($dateTime); 
        $newDateTime->setTimezone(new DateTimeZone("UTC")); 
        $dateTimeUTC = $newDateTime->format("Y-m-d H:i:s");
        return $dateTimeUTC;
    }

    public static function getConvertedDateTime($dateTime,$format = ''){

        $siteTimeZone = Config::get('constants.site_timezone');
        $dateTime = date("Y-m-d H:i:s",strtotime($dateTime)); 
        $newDateTime = new DateTime($dateTime); 
        $newDateTime->setTimezone(new DateTimeZone($siteTimeZone)); 
        if(!empty($format)){
            $dateTimeUTC = $newDateTime->format($format);
        }else{
            $dateTimeUTC = $newDateTime->format("Y-m-d H:i A");
        }
        return $dateTimeUTC;
    }

    public static function uploadImages($file,$path,$type = null){ 
        $uploadPath = self::getConfigValue('upload_path').$path;

        // Upload base image
        if($type != 0){
            $imagePath = $file->store($uploadPath);
            $fileName = basename($imagePath);

            // create thumb 
            $uploadPath = $uploadPath.'thumb/';
            $img = Image::make($file->getRealPath());
            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();                 
            });
            $img->stream();
            Storage::disk('local')->put($uploadPath.$fileName, $img, 'public');
        }
        // Upload base image

        // start thumb image $type = 0 make thumb
        if($type == 0){
            $img = Image::make($file->getRealPath());
            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();                 
            });
            $img->stream();
            $fileName = \Illuminate\Support\Str::random(40).'.'.$file->extension();
            Storage::disk('local')->put($uploadPath.$fileName, $img, 'public');
        }
        // start thumb image
      
        $responseArr['filename'] = $fileName;
        return $responseArr;
    }

    public static function getImageUrl($filename,$path,$type){
        $imageUrl = '';
        $linkPath = self::getConfigValue('link_path');
        if($type == 0){
            $imageUrl = asset($linkPath.$path.''.$filename);
        }else{
            $imageUrl = asset($path.'thumb/'.$filename);
        }
        return $imageUrl;
    }


    public static function removeUploadedImages($file_name,$path){
        $fileFullPath = self::getConfigValue('storage_path').$path;
        $filePath = storage_path($fileFullPath . $file_name);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public static function getConfigValue($key){
        $configValue = Config::get('constants.'.$key);
        if(!empty($configValue)){
            return $configValue;
        }else{
            return '';
        }
    }

    public static function shortString($string,$len = 50){
        $out = strlen($string) > $len ? mb_substr($string,0,$len)."..." : $string;
        return $out;
    }
}