<?php
class AccountValidation{ 

    static function create($data){
        extract($data);
        $error_array= array();
        if(empty($client_id)){
            array_push($error_array, 'Client is required');
        };
        if(empty($address)){
            array_push($error_array, 'Address / Account Number is required');
        };
        if(empty($tag_id)){
            array_push($error_array, 'Tag is required');
        };
        if(count($error_array) > 0){
            return RHelper::response(false, 400, json_encode($error_array));
        }



    }
}

?>