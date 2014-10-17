<?php

require_once 'models/Magazine.php';

class MagazineApiController{
    
    public static function get(Array $args = NULL){
        if($args && is_numeric($args[0])) {
            return Magazine::get($args[0]);
        }
        else {
            return Magazine::getAll();
        }
    }
    
    public static function save($m) {
        $temp = new Magazine();
        return $temp->save();
    }
}