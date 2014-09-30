<?php

/**
 * Description of Magazine
 *
 * @author Nives
 */
class Magazine 
{
    public $number;
    public $cover;
    public $color;
    public $published;
    public $publish_date;
    public $abstract;
    public $content;
    
    public function __construct() {}
    
    public static function getAll(){
        $magazine_array = array();
        foreach( new DirectoryIterator(DATA_PATH."/magazines/") as $file_info ) {
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
        if( file_exists(DATA_PATH."/magazines/{$id}.txt") === false ) {
            throw new Exception('Todo ID is invalid');
        }
		
        $serialized = file_get_contents(DATA_PATH."/magazines/{$id}.txt");
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
        if( file_exists(DATA_PATH."/magazines/{$id}.txt") === false ) {
            throw new Exception('Todo ID does not exist!');
        }
		
        unlink(DATA_PATH."/magazines/{$id}.txt");
        return true;
    }
	
    public static function save(Magazine $m)
    {
        //get the array version of this todo item
        $todo_item_array = $m->toArray();
		
        //save the serialized array version into a file
        $success = file_put_contents(DATA_PATH."/magazines/{$this->number}.txt", serialize($todo_item_array));
		
        //if saving was not successful, throw an exception
        if( $success === false ) {
            throw new Exception('Failed to save todo item');
        }
		
        //return the array version
        return $todo_item_array;
    }
	
    public function toArray()
    {
        //return an array version of the todo item
        return array(
            'todo_id' => $this->todo_id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'is_done' => $this->is_done
        );
    }
}
