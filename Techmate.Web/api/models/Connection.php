<?php
define('HOST', 'mongodb://mongolab.com');
define('PORT', 35260);
define('UTENTE', 'site');
define('PASS', 'admin');
define('DB', 'techmate');

class Connection {
    static $db = NULL;

    static function getConnection()
    {
        if (self::$db === null)
        {
            try {
                $m = new MongoClient("mongodb://site:admin@ds035260.mongolab.com:35260/techmate");
            } catch (MongoConnectionException $e) {
                die('Failed to connect to MongoDB '.$e->getMessage());
            }
            self::$db = $m->selectDB('techmate');
        }

        return self::$db;
    }
}

?>
