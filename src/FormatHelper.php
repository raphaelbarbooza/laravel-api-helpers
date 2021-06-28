<?php


namespace laravelApiHelpers;


class FormatHelper
{

    public static function apenasNumeros($string){
        return preg_replace("/[^0-9]/", "", $string);
    }

    public static function formatarUrl($url, $protocolo = 'http'){
        //Checar se a url já tem protocolo
        if(str_contains($url,'http://')){
            return $url;
        }
        if(str_contains($url,'https://')){
            return $url;
        }
        if($protocolo == 'server'){
            if (isset($_SERVER['HTTPS']) &&
                ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
                isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $protocol = 'https://';
            }
            else {
                $protocol = 'http://';
            }
            //Retornar
            return $protocol.$url;
        }
        //Retornar padrão
        return $protocolo.'://'.$url;
    }

}
