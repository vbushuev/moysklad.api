<?php namespace App\Common;
class Log{
    public static function log(){
        foreach(func_get_args() as $s){
            if(is_object($s) || is_array($s)) echo date('Y-m-d H:i:s')."\tLOG\t".json_encode($s,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n";
            else if(is_string($s) ) echo date('Y-m-d H:i:s')."\tLOG\t".$s."\n";
        }
    }
}
?>
