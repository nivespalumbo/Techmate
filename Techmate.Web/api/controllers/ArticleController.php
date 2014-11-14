<?php

class ArticleController {
    public static function get($idMagazine) {
        return Article::get(intval($idMagazine));
    }
    
    public static function save($data) {
        $a = new Article();
        foreach ($data as $key => $value) {
            $a->$key = $value;
        }
        return $a->save();
    }
    
    public static function delete($numberArticle, $idMagazine){
        return Article::delete(intval($numberArticle), intval($idMagazine));
    }
}