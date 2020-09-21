<?php
class AuthValidation{ 

    static function signup($data){
        extract($data);
        $error_array= array();
        if(empty($fullname)){
            array_push($error_array, 'Fullname is required');
        };
        if(empty($email)){
            array_push($error_array, 'Email address is required');
        }
        if(empty($pwd)){
            array_push($error_array, 'Password is required');
        };
        if(count($error_array) > 0){
            return Helper::response(false, 400, json_encode($error_array));
        }



    }
}

?>