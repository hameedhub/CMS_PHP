<?php

class Helper{

    public static function response($status, $code, $msg, $data= '' ){
       $res = array(  'status'=> $status,
                'code'=> $code,
                'message'=> $msg,
                'data'=> $data
        );
        return json_encode($res);
    
    }

}
