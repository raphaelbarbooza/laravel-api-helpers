<?php


namespace laravelApiHelpers;


class AssetHelper
{

    public static function get($file){
        return  config('app.files_url').'public/get/'.$file;
    }

    public static function download($file){
        return  config('app.files_url').'public/download/'.$file;
    }

}