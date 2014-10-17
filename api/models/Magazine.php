<?php

/**
 * Description of Magazine
 *
 * @author Nives
 */
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
        $magazine_array = array();
        foreach( new DirectoryIterator("data/magazines/") as $file_info ) {
            if( $file_info->isFile() == true ) {
                $serialized = file_get_contents($file_info->getPathname());
                $unserialized = unserialize($serialized);
                
                $magazine = new Magazine();
		$magazine->number = $unserialized['number'];
		$magazine->cover = $unserialized['cover'];
		$magazine->color = $unserialized['color'];
		$magazine->published = $unserialized['published'];
                $magazine->publish_date = $unserialized['publish_date'];
		$magazine->abstract = $unserialized['abstract'];
		$magazine->content = $unserialized['content'];
                
                $magazine_array[] = $magazine;
            }
        }

        return $magazine_array;
    }
    
    public static function get($id){
        if( file_exists("data/magazines/{$id}.txt") === false ) {
            throw new Exception('ID is invalid');
        }
		
        $serialized = file_get_contents("data/magazines/{$id}.txt");
        $unserialized = unserialize($serialized);
		
        $magazine = new Magazine();
        $magazine->id = $unserialized['id'];
        $magazine->number = $unserialized['number'];
        $magazine->cover = $unserialized['cover'];
        $magazine->color = $unserialized['color'];
        $magazine->published = $unserialized['published'];
        $magazine->publish_date = $unserialized['publish_date'];
        $magazine->abstract = $unserialized['abstract'];
        $magazine->content = $unserialized['content'];

        return $magazine;
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
