<?php

class Accounts{

    static function create($data, $user=false){
        $val = AccountValidation::create($data);
        if ($val) {
            return $val;
        }
        extract($data);
       

        $check = DB::query('SELECT address,tag_id FROM accounts WHERE address=:address AND tag_id=:tag_id', 
        array(':address' => $address,
        ':tag_id' => $tag_id
    ));
        if (count($check) > 0) {
            return   RHelper::response(false, 409, 'Account already exist');
        }
        $q = DB::query('INSERT INTO `accounts`(`client_id`, `tag_id`, `type_id`, `address`,`status`) 
        VALUES (:client_id, :tag_id,:type_id,:address,:status)', 
        array(
            ':client_id' => $client_id,
            ':tag_id' => $tag_id,
            ':type_id' => $type_id,
            ':address' => $address,
            ':status'=> $status
        ));
        return RHelper::response(true, 201, 'Account successfully created', $q);
    }
    static function show(){
    }
    static function index (){
        $q = DB::query('SELECT * FROM accounts WHERE io = "ON" ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q);
    }
    static function update($data){
        
    }
    static function destroy(){
    }
    static function myAccounts(){
    }
    static function createOption($data, $option){
        extract($data);
        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT name FROM '.$option.' WHERE name=:name', array(':name' => $name));
        if (count($check) > 0) {
            return   RHelper::response(false, 409, ucfirst($option).' name already exist');
        }
        $q = DB::query('INSERT INTO '.$option.' (`name`, `description`) 
        VALUES (:name,:description)', array(':name' => $name, ':description' => $description));
        return RHelper::response(true, 201, ucfirst($option).' successfully created', $q);
    }
    static function updateOption($data, $option){
        extract($data);
        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT name FROM '.$option.' WHERE id=:id', array(':id' => $id));
        if (count($check) > 0) {
            $name = empty($name) ? $check['name'] : $name;
            $description = empty($description) ? $check['description'] : $description;

            $q = DB::query('UPDATE '.$option.' SET name=:name, description=:description WHERE id=:id', array(
                ':id' => $id,
                ':name' => $name,
                ':description' => $description
            ));
            return RHelper::response(true, 200, ucfirst($option).' was successfuly updated', $q);
        }

    }
    static function indexOption($option){
        $q = DB::query('SELECT * FROM '.$option.' WHERE io="ON" ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q);
    }
    static function showOption($option, $id){
        $q = DB::query('SELECT * FROM '.$option.' WHERE io="ON" AND "'.$id.'" ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q[0]);
    }
    static function removeOption($data, $option){
        extract($data);

        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT id FROM '.$option.' WHERE id=:id  AND io = "ON"', array(':id' => $id));

        if (count($check) > 0) {
            $q = DB::query('UPDATE '.$option.' SET io = "OFF"  WHERE id=:id', array(':id' => $id));
            return RHelper::response(true, 200, ucfirst($option).' was successfuly deleted', $q);
        } else {
            return RHelper::response(false, 404, ucfirst($option).' not found');
        }
    }
    static function delOption($session_id, $user_id){
        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
    
        $q = DB::query('SELECT *, "account_tag" as option FROM account_tag WHERE io = "OFF" ORDER BY id DESC');
        $q1 = DB::query('SELECT *, "account_type" as option FROM account_type WHERE io = "OFF" ORDER BY id DESC');
        $data = array_merge($q,$q1);

        return RHelper::response(true, 200, 'success', $data);
    }
    static function restoreOption($data)
    {

        extract($data);

        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT id FROM '.$option.' WHERE id=:id  AND io = "OFF"', array(':id' => $id));

        if (count($check) > 0) {
            $q = DB::query('UPDATE '.$option.' SET io = "ON"  WHERE id=:id', array(':id' => $id));
            return RHelper::response(true, 200, ucfirst($option).' was successfuly restored', $q);
        } else {
            return RHelper::response(false, 404, ucfirst($option).' not found');
        }
    }

}

?>