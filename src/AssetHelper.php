<?php


namespace laravelApiHelpers;


class AssetHelper
{

    public static function get($file){
        return  config('files_url').'public/get/'.$file;
    }

    public static function download($file){
        return  config('files_url').'public/download/'.$file;
    }

}