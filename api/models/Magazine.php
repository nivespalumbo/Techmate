<?php

/**
 * Description of Magazine
 *
 * @author Nives
 */
require_once 'Connection.php';

class Magazine 
{
    public $id;
    public $number;
    public $cover;
    public $color;
    public $published;
    public $publish_date;
    public $abstract;
    public $content;
    
    public function __construct() { }
    
    public static function getAll(){
        $db = new Connection();
        return $db->getCollection("magazines");
    }
    
    public static function get($id){
        
    }
    
    public static function delete($id)
    {
        if( file_exists("data/magazines/{$id}.txt") === false ) {
            throw new Exception('ID does not exist!');
        }
		
        unlink("data/magazines/{$id}.txt");
        return true;
    }
	
    public function save()
    {
        //get the array version of this todo item
        $magazine_array = $this->toArray();
		
        //save the serialized array version into a file
        $success = file_put_contents("data/magazines/{$this->number}.txt", serialize($magazine_array));
		
        //if saving was not successful, throw an exception
        if( $success === false ) {
            throw new Exception('Failed to save magazine');
        }
		
        //return the array version
        return $this;
    }
	
    public function toArray()
    {
        //return an array version of the todo item
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
