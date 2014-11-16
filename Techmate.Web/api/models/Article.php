<?php

/**
 * Description of Article
 *
 * @author Nives
 */
require_once 'Connection.php';

class Article 
{
    private static $PROJECTION = array(
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
        $db = Connection::getConnection();
        $collection = $db->articles;
        
        return iterator_to_array($collection->find(array('magazine'=>$idMagazine), Article::$PROJECTION)->sort(array('number' => 1)));
    }
    
    public function save() {
        $db = Connection::getConnection();
        $collection = $db->articles;
        
        try {
            $newObject = $this->toArray();
            $collection->update(
                array('number' => $newObject['number'], 'magazine' => $newObject['magazine']),
                array('$set' => $newObject),
                array('upsert' => true)
            );
            return $collection->findOne(array('number' => $newObject['number'], 'magazine' => $newObject['magazine']), Article::$PROJECTION);

        } catch(MongoCursorException $e) {
            echo $e->message();
        }
    }
    
    public static function delete($numberArticle, $idMagazine) {
        $db = Connection::getConnection();
        $collection = $db->articles;
        
        try {
            return $collection->remove(array("number" => $numberArticle, 'magazine' => $idMagazine));
        } catch(MongoCursorException $e) {
            echo $e->message();
        }
    }
    
    function toArray() {
        return array(
            'number' => intval($this->number),
            'author' => $this->author,
            'link' => $this->link,
            'section' => $this->section,
            'magazine' => intval($this->magazine),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'text' => $this->text,
            'images' => $this->images,
            'attachments' => $this->attachments
        );
    }
}
