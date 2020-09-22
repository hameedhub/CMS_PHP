<?php
class SKey
{

    static function session($key, $id)
    {
        $check = DB::query('SELECT session_id FROM users WHERE session_id=:session_id AND id=:id', array(
            ':session_id' => $key,
            ':id' => $id
        ));
        if (empty($check)) {
            return RHelper::response(false, 400, 'Invalid permission, login and try again');
        }
    }
}
