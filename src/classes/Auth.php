<?php

class Auth
{

    static function create($data)
    {
        $val = AuthValidation::signup($data);
        if($val){
            return $val;
        }
        extract($data);
        $check = DB::query('SELECT email FROM users WHERE email=:email', array(':email'=> $email));
        if(empty($check)){
            $q = DB::query('INSERT INTO `users`( `fullname`, `email`, `pwd`) 
            VALUES (:fullname,:email,:pwd)', array(':fullname'=>$fullname, 'email'=>$email, ':pwd'=> password_hash($pwd, PASSWORD_DEFAULT)));
            return Helper::response(true, 201, 'User was successfully added', $q);
        }else{
            return Helper::response(false, 409, 'Email address already exist');
        }
       
    }
    public static function login($data)
    {
        extract($data);
        if (!empty($email) || !empty($pwd)) {
            $q = DB::query('SELECT * FROM users WHERE email =:email', array(':email' => $email));

            if (count($q) > 0) {
                if (password_verify($pwd, $q[0]['password'])) {

                    Session::init();
                    Session::set('isLoggedIn', true);
                    Session::set('user', $q[0]);
                    return Helper::response(true, 200, 'Login was successful', $q[0]);
                } else {
                    return  Helper::response(false, 400, 'Invalid email or password');
                }
            } else {
                return   Helper::response(false, 400, 'Invalid email or password');
            }
        } else {
            return  Helper::response(false, 400, 'Email and password is required');
        }
    }
    static function fpwd($email)
    {
    }
    static function cpwd($opwd, $npwd)
    {
    }
}
