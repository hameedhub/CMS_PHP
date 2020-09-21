<?php

class Auth
{

    static function create($data)
    {
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
