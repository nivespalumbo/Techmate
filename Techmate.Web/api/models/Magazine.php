<?php

/**
 * Description of Magazine
 *
 * @author Nives
 */
require_once 'Connection.php';

class Magazine 
{  
    static $PROJECTION = array(
        'number' => true, 
        'cover' => true, 
        'color' => true, 
        'published' => true, 
        'publish_date' => true, 
        'abstract' => true, 
        'content' => true, 
        '_id' => false
    );
    
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
        
        return iterator_to_array($collection->find(array(), Magazine::$PROJECTION)->sort(array('number' => 1)));
    }
    
    public static function getPublished(){
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        return iterator_to_array($collection->find(array('published'=>true), Magazine::$PROJECTION)->sort(array('number' => 1)));
    }
    
    public static function get($number){
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        return $collection->findOne(array('number' => $number), Magazine::$PROJECTION);
    }
    
    public static function delete($number)
    {
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        try {
            return $collection->remove(array("number" => $number));
        } catch(MongoCursorException $e) {
            echo $e->message();
        }
    }
    
    public static function publish($number)
    {
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        try {
            return $collection->update(
                array('number' => $number),
                array('$set' => array('published' => true))
            );
            
        } catch(MongoCursorException $e) {
            echo $e->message();
        }
        return false;
    }
	
    public function save()
    {
        $db = Connection::getConnection();
        $collection = $db->magazines;
        
        try {
            $newObject = $this->toArray();
            $collection->update(
                array('number' => intval($this->number)),
                array('$set' => $newObject),
                array('upsert' => true)
            );
            return $collection->findOne(array('number' => $newObject['number']), Magazine::$PROJECTION);

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
