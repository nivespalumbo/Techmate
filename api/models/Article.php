<?php

/**
 * Description of Article
 *
 * @author Nives
 */
class Article 
{
    public $id;
    public $author;
    public $link;
    public $section;
    public $magazine;
    public $title;
    public $subtitle;
    public $text;
    public $images;
    public $attachments;
    
    public function __construct($id, $author, $link, $section, Magazine $magazine=NULL, $title=NULL, $subtitle=NULL, $text=NULL, Array $images=NULL, Array $attachments=NULL) {
        $this->id = $id;
        $this->author = $author;
        $this->link = $link;
        $this->section = $section;
        $this->magazine = $magazine;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->text = $text;
        $this->images = $images;
        $this->attachments = $attachments;
    }
}
