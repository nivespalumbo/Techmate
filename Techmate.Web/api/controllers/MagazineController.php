<?php

require_once 'models/Magazine.php';

class MagazineController{
    
    public static function get($id = NULL) {
        if ($id) {
            return Magazine::get($id);
        } else {
            return Magazine::getPublished();
        }
    }
    
    public static function getAll() {
        return Magazine::getAll();
    }
    
    public static function save($data) {
        $m = new Magazine();
        foreach ($data as $key => $value) {
            $m->$key = $value;
        }
        return $m->save();
    }
    
    public static function delete($id){
        return Magazine::delete($id);
    }
}