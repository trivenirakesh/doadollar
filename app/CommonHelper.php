<?php 

namespace App\Helpers;
use Illuminate\Support\Facades\Config;
use DateTime;
use DateTimeZone;

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
}