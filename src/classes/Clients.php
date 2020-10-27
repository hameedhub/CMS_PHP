<?php
class Client
{
    static function create($data)
    {
        $val = ClientValidation::create($data);
        if ($val) {
            return $val;
        }
       
        extract($data);

        $check = DB::query('SELECT email FROM clients WHERE email=:email', array(':email' => $email));

        
        if (empty($check)) {

            if($_FILES['avatar']['name']){
                $image = $_FILES['avatar']['name'];
                $avatar = uniqid('', true) . '.' . $image;
                $target = "../storage/clients/" . basename($avatar);
            }

            $q = DB::query(
                'INSERT INTO `clients`(`firstname`, `lastname`, `email`, `password`, `phone`, `address`, `city`, `state`, `zipcode`, `country`, 
            `occupation`, `date_of_birth`, `gender`, `marital_status`, `account_id`, `currency_id`, `avatar`)
             VALUES (:firstname,:lastname,:email,:password,:phone,:address,:city,:state,:zipcode,:country,:occupation,:date_of_birth,:gender,:marital_status,
             :account_id,:currency_id,:avatar)',
                array(
                    ':firstname' => $firstname,
                    ':lastname' => $lastname,
                    ':email' => $email,
                    ':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':phone' => $phone,
                    ':address' => $address,
                    ':city' => $city,
                    ':state' => $state,
                    ':zipcode' => $zipcode,
                    ':country' => $country,
                    ':occupation' => $occupation,
                    ':date_of_birth' => $date_of_birth,
                    ':gender' => $gender,
                    ':marital_status' => $marital_status,
                    ':account_id' => intval($account_id),
                    ':currency_id' => intval($currency_id),
                    ':avatar' => $avatar
                )
            );
            if($_FILES['avatar']['name']){
                move_uploaded_file($_FILES['avatar']['tmp_name'], $target);
            }
           
            return RHelper::response(true, 201, 'Registeration was successfully', $q);
        } else {
            return RHelper::response(false, 409, 'Email address already exist');
        }
    }
    static function index()
    {
       
        $q = DB::query('SELECT * FROM clients WHERE io = "ON" ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q);
    }
    static function show($id)
    {
       
        $q = DB::query('SELECT * FROM clients WHERE io = "ON" AND id ="'.$id.'" ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q[0]);
    }
    static function update($data, $user = false)
    {   
       
        if($_FILES['avatar']['name']){
            $image = $_FILES['avatar']['name'];
            $avatar = uniqid('', true) . '.' . $image;
            $target = "../storage/clients/" . basename($avatar);
           
            // $img = DB::query('SELECT avatar FROM clients WHERE id=:id', array(':id' => $data['client_id']));
            // // unset previous image
            // unlink('../storage/clients/'.$img['avatar']);
        }

        extract($data);
        if (!$user) {
            $skey = SKey::session($session_id, $user_id);
            if ($skey) {
                return $skey;
            };
        }

        $check = DB::query('SELECT * FROM clients WHERE id=:id', array(':id' => $client_id));
       
        if (count($check) > 0) {
            $firstname = empty($firstname) ? $check[0]['firstname'] : $firstname;
            $lastname = empty($lastname) ? $check[0]['lastname'] : $lastname;
           
            $phone = empty($phone) ? $check[0]['phone'] : $phone;
            $address = empty($address) ? $check[0]['address'] : $address;
            $city = empty($city) ? $check[0]['city'] : $city;
            $state = empty($state) ?$check[0]['state'] : $state;
            $zipcode = empty($zipcode) ? $check[0]['zipcode'] : $zipcode;
            $country = empty($country) ? $check[0]['country'] : $country;
            $occupation = empty($occupation) ? $check[0]['occupation'] : $occupation;
            $date_of_birth = empty($date_of_birth) ? $check[0]['date_of_birth'] : $date_of_birth;
            $gender = empty($gender) ? $check[0]['gender'] : $gender;
            $marital_status = empty($marital_status) ? $check[0]['marital_status'] : $marital_status;
            $account_id = empty($account_id) ? $check[0]['account_id'] : $account_id;
            $currency_id = empty($currency_id) ? $check[0]['currency_id'] : $currency_id;
            $avatar = $_FILES['avatar']['name'] ? $avatar : $check[0]['avatar'];
            // print_r($check[0]['avatar']);
            // echo $avatar;
            // die;
           echo $q = DB::query('UPDATE `clients` SET `firstname`=:firstname,`lastname`=:lastname,`phone`=:phone,`address`=:address,`city`=:city,
            `state`=:state,`zipcode`=:zipcode,`country`=:country,`occupation`=:occupation,`date_of_birth`=:date_of_birth,`gender`=:gender,`marital_status`=:marital_status,
            `account_id`=:account_id,`currency_id`=:currency_id,`avatar`=:avatar WHERE id=:id', array(
                ':id' => $client_id,
                ':firstname' => $firstname,
                ':lastname' => $lastname,
               
                ':phone' => $phone,
                ':address' => $address,
                ':city' => $city,
                ':state' => $state,
                ':zipcode' => $zipcode,
                ':country' => $country,
                ':occupation' => $occupation,
                ':date_of_birth' => $date_of_birth,
                ':gender' => $gender,
                ':marital_status' => $marital_status,
                ':account_id' => $account_id,
                ':currency_id' => $currency_id,
                ':avatar' => $avatar
            ));
            if($_FILES['avatar']['name']){
                move_uploaded_file($_FILES['avatar']['tmp_name'], $target);
            }
            return RHelper::response(true, 200, 'Update was successful', $q);
        }
    }
    static function destroy($data)
    {

        extract($data);

        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT id FROM clients WHERE id=:id  AND io = "ON"', array(':id' => $client_id));

        if (count($check) > 0) {
            $q = DB::query('UPDATE clients SET io = "OFF"  WHERE id=:id', array(':id' => $client_id));
            return RHelper::response(true, 200, 'Client was successfuly deleted', $q);
        } else {
            return RHelper::response(false, 404, 'Client not found');
        }
    }
    static function del($session_id, $user_id){
        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $q = DB::query('SELECT * FROM clients WHERE io = "OFF" ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q);
    }
    static function restore($data)
    {

        extract($data);

        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT id FROM clients WHERE id=:id  AND io = "OFF"', array(':id' => $client_id));

        if (count($check) > 0) {
            $q = DB::query('UPDATE clients SET io = "ON"  WHERE id=:id', array(':id' => $client_id));
            return RHelper::response(true, 200, 'Client was successfuly restored', $q);
        } else {
            return RHelper::response(false, 404, 'Client not found');
        }
    }
}
