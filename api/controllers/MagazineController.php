<?php

require_once 'models/Magazine.php';

class MagazineController{
    
    public static function get(Array $args = NULL){
        if($args && is_numeric($args[0])) {
            return Magazine::get($args[0]);
        }
        else {
            return Magazine::getAll();
        }
    }
    
    public static function save($data) {
        $m = new Magazine();
        foreach ($data as $key => $value) {
            $m->$key = $value;
        }
        return $m->save();
    }
}