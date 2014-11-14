<?php

/**
 * Description of Article
 *
 * @author Nives
 */
class Article 
{
    static $PROJECTION = array(
        'number' => true,
        'author' => true,
        'link' => true,
        'section' => true,
        'magazine' => true,
        'title' => true,
        'subtitle' => true,
        'text' => true,
        'images' => true,
        'attachments' => true, 
        '_id' => false
    );
    
    public $number;
    public $author;
    public $link;
    public $section;
    public $magazine;
    public $title;
    public $subtitle;
    public $text;
    public $images;
    public $attachments;
    
    public function __construct() { }
    
    public static function get($idMagazine) {
        
    }
    
    public function save() {
        
    }
    
    public static function delete($numberArticle, $idMagazine) {
        
    }
    
    function toArray() {
        return array(
            'number' => $this->number,
            'author' => $this->author,
            'link' => $this->link,
            'section' => $this->section,
            'magazine' => $this->magazine,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'text' => $this->text,
            'images' => $this->images,
            'attachments' => $this->attachments
        );
    }
}
