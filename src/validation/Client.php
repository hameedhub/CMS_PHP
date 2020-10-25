<?php
class ClientValidation{ 

    static function create($data){
        extract($data);
        $error_array= array();
     
        if(empty($email)){
            array_push($error_array, 'Email address is required');
        }
        if(empty($password)){
            array_push($error_array, 'Password is required');
        };
        if(count($error_array) > 0){
            return RHelper::response(false, 400, json_encode($error_array));
        }



    }
}

?>