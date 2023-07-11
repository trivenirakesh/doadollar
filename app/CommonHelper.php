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

    public static function getConvertedDateTime($dateTime){

        $siteTimeZone = Config::get('constants.site_timezone');
        $dateTime = date("Y-m-d H:i:s",strtotime($dateTime)); 
        $newDateTime = new DateTime($dateTime); 
        $newDateTime->setTimezone(new DateTimeZone($siteTimeZone)); 
        $dateTimeUTC = $newDateTime->format("Y-m-d H:i A");
        return $dateTimeUTC;
    }

    public static function uploadImages($file,$path){
        $public = 'public/';
        $uploadPath = $path;
        $thumbUploadPath = $path.'thumb/';
        $fileName = date('YmdHis') . '.' . $file->extension();
      
        // start base image
        $path = Storage::putFileAs($public.$uploadPath, $file, $fileName);
        // start base image
      
        // start thumb image
        $img = Image::make($file->getRealPath());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();                 
        });
        $img->stream();
        Storage::disk('local')->put($public.$thumbUploadPath.$fileName, $img, 'public');
        // start thumb image
      
        $responseArr['filename'] = $fileName;
        $responseArr['path'] = $public.'storage/'.$uploadPath;
        $responseArr['thumbpath'] = $public.'storage/'.$thumbUploadPath;
        return $responseArr;
    }

    public static function getImageUrl($filename,$path,$type){
        $imageUrl = '';
        if($type == 0){
            $imageUrl = asset($path.''.$filename);
        }else{
            $imageUrl = asset($path.'thumb/'.$filename);
        }
        return $imageUrl;
    }

    // public static function removeUploadedImages($fileName,$path){

    //     if(Storage::exists($fileName)){
    //         Storage::delete($fileName);
    //         return true;
    //       }else{
    //         return false;
    //       }
    // }
}