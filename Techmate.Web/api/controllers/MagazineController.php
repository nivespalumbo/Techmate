<?php

require_once 'models/Magazine.php';

class MagazineController {
    
    public static function get($number = NULL) {
        if ($number) {
            return Magazine::get(intval($number));
        } else {
            return Magazine::getPublished();
        }
    }
    
    public static function getAll() {
        return Magazine::getAll();
    }
    
    public static function publish($number) {
        return Magazine::publish(intval($number));
    }
    
    public static function save($data) {
        $m = new Magazine();
        foreach ($data as $key => $value) {
            $m->$key = $value;
        }
        return $m->save();
    }
    
    public static function delete($number){
        return Magazine::delete(intval($number));
    }
}
