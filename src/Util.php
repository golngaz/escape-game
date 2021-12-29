<?php

namespace App;

/**
 *
 */
class Util
{

    public static function putIni(string $fileName, array $data)
    {

        $res = array();
        foreach($data as $key => $datum)
        {
            if(is_array($datum))
            {
                $res[] = "[$key]";
                foreach($datum as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
            }
            else $res[] = "$key = ".(is_numeric($datum) ? $datum : '"'.$datum.'"');
        }

        file_put_contents($fileName, implode("\r\n", $res));
    }
}