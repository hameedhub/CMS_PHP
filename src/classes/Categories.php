<?php
class Categories
{

    static function create($data)
    {
        extract($data);
        $skey = SKey::session($session_id, $id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT name FROM categories WHERE name=:name', array(':name' => $name));
        if (count($check) > 0) {
            return   RHelper::response(false, 409, 'Category name already exist');
        }
        $q = DB::query('INSERT INTO `categories`(`name`, `description`) 
        VALUES (:name,:description)', array(':name' => $name, ':description' => $description));
        return RHelper::response(true, 201, 'Category successfully created', $q);
    }
    static function index()
    {
        $q = DB::query('SELECT * FROM categories ORDER BY id DESC');
        return RHelper::response(true, 200, 'success', $q);
    }
    static function show($id)
    {
    }
    static function destroy($id)
    {
    }
}
