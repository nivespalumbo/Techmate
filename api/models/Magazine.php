<?php

/**
 * Description of Magazine
 *
 * @author Nives
 */
require_once 'Connection.php';

class Magazine 
{  
    public $_id = NULL;
    public $number;
    public $cover;
    public $color;
    public $published = false;
    public $publish_date;
    public $abstract;
    public $content;
    
    public function __construct() { }
    
    public static function getAll(){
        $db = Connection::getConnection();
        $collection = $db->magazines;
        return iterator_to_array($collection->find());
    }
    
    public static function getPublished(){
        $db = Connection::getConnection();
        $collection = $db->magazines;
        return iterator_to_array($collection->find(array('published'=>true)));
    }
    
    public static function get($id){
        $db = Connection::getConnection();
        $collection = $db->magazines;
        return $collection->findOne(array('_id' => new MongoId($id)));
    }
    
    public static function delete($id)
    {
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        try {
            return $collection->remove(array("_id" => new MongoId($id)));
        } catch(MongoCursorException $e) {
            echo $e->message();
        }
    }
	
    public function save()
    {
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        try {
            $newObject = $this->toArray();
            $collection->update(
                array('number' => $this->number),
                array('$set' => $newObject),
                array('upsert' => true)
            );
            return $collection->findOne(array('number' => $newObject['number']));

        } catch(MongoCursorException $e) {
            echo $e->message();
        }
    }
    
    function toArray() {
        return array(
            'number' => $this->number,
            'cover' => $this->cover,
            'color' => $this->color,
            'published' => $this->published,
            'publish_date' => $this->publish_date,
            'abstract' => $this->abstract,
            'content' => $this->content
        );
    }
}
