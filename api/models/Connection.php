<?php
define('HOST', 'mongodb://mongolab.com');
define('PORT', 35260);
define('UTENTE', 'site');
define('PASS', 'admin');
define('DB', 'techmate');

class Connection {
    private $link;
    private $db;
    private $collection;

    function __construct() {
        $this->link = new Mongo("mongodb://site:admin@ds035260.mongolab.com:35260/techmate") or die("Error on connection to MongoDB");
        $this->db = $this->link->selectDB("techmate");
    }
    
    function getCollection($collectionName) {
        if($this->db != NULL) {
            if($collectionName == "magazines" || $collectionName == "articles") {
                $this->collection = $this->db->$collectionName;
                return iterator_to_array($this->collection->find());
            }
        }
        return NULL;
    }
    
    //    static $db = NULL;
//
//    static function getMongoCon()
//    {
//        if (self::$db === null)
//        {
//            try {
//                $m = new Mongo('mongodb://'.$MONGO['servers'][$i]['mongo_host'].':'.$MONGO['servers'][$i]['mongo_port']);
//
//            } catch (MongoConnectionException $e) {
//                die('Failed to connect to MongoDB '.$e->getMessage());
//            }
//            self::$db = $m;
//        }
//
//        return self::$db;
//    }
}

?>
