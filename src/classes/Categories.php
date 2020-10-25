<?php
class Categories
{

    static function create($data)
    {
        extract($data);
        $skey = SKey::session($session_id, $user_id);
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
        $q = DB::query('SELECT * FROM categories WHERE id=:id', array(':id' => $id));
        return RHelper::response(true, 200, 'success', $q);
    }
    static function update($data)
    {
        extract($data);
        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $check = DB::query('SELECT name FROM categories WHERE id=:id', array(':id' => $id));
        if (count($check) > 0) {
            $name = empty($name) ? $check['name'] : $name;
            $description = empty($description) ? $check['description'] : $description;

            $q = DB::query('UPDATE categories SET name=:name, description=:description WHERE id=:id', array(
                ':id' => $id,
                ':name' => $name,
                ':description' => $description
            ));
            return RHelper::response(true, 200, 'category was successfuly updated', $q);
        }
    }
    static function destroy($data)
    {
        extract($data);
        $skey = SKey::session($session_id, $user_id);
        if ($skey) {
            return $skey;
        };
        $q = DB::query('DELETE FROM categories WHERE id=:id', array(':id'=> $id));
        return RHelper::response(true, 200, 'category was successfuly deleted', $q);

    }
}
