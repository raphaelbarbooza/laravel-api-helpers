<?php


namespace laravelApiHelpers;


class FormatHelper
{

    public static function apenasNumeros($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    public static function formatarUrl($url, $protocolo = 'http')
    {
        //Checar se a url já tem protocolo
        if (str_contains($url, 'http://')) {
            return $url;
        }
        if (str_contains($url, 'https://')) {
            return $url;
        }
        if ($protocolo == 'server') {
            if (isset($_SERVER['HTTPS']) &&
                ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
                isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $protocol = 'https://';
            } else {
                $protocol = 'http://';
            }
            //Retornar
            return $protocol . $url;
        }
        //Retornar padrão
        return $protocolo . '://' . $url;
    }

    public static function tratarResponse($objeto, $remover_nulos = true, $remover_chaves = [], $busca_recursiva = false)
    {
        //Vai pegar o objeto, identifcar rasamente o que ele é, e tratar antes de retornar para o Response da API
        if (is_array($objeto)) {
            //É uma array, vamos tratar como tal.
            $array = $objeto;
        } else {
            //Provavelmente é um objeto. Vamos ver se podemos transformar em array
            if (method_exists($objeto, 'toArray')) {
                $array = $objeto->toArray();
            } else {
                throw new \Exception("O objeto informado não pode ser tratado.");
            }
        }
        //Agora vamos percorrer todo o vetor
        foreach ($array as $indice => $valor) {
            //Caso o $valor for outra array, e a busca recursiva estiver ativa, vamos reaplicar o método nele.
            if (is_array($valor) && $busca_recursiva) {
                $array[$indice] = self::tratarResponse($valor, $remover_nulos, $remover_chaves, $busca_recursiva);
            }
            //Caso o $valor for nulo e o remover nulos ativo, vamos apagar a chave
            if (is_null($valor) && $remover_nulos) {
                unset($array[$indice]);
            }
            //Caso o indice pertencer a chaves que devem ser removidas, remover
            if (in_array($indice, $remover_chaves)) {
                unset($array[$indice]);
            }
        }

        //Retornar o objeto tratado
        return $array;
    }

    public static function mascarar($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }


}
