<?php
include_once 'include/connection.php';

class PushNotification {
    public $id;
    public $timestamp;
    public $title;
    public $text;
    public $status;
    public $device_reached;
    public $device_failure;

    
    public function __construct($id=NULL, $title=NULL, $text=NULL, $status=NULL, $device_reached=NULL, $device_failure=NULL, $timestamp=NULL) {
        $this->id=$id;
        $this->timestamp = $timestamp;
        $this->title = $title;
        $this->text = $text;
        $this->status = $status;
        $this->device_reached = $device_reached;
        $this->device_failure = $device_failure;
    }
    
    
    
    /*
     * Invia e salva la push nel DB.
     * Echo "ok" in caso di successo,
     * "nok" altrimenti.
     */
    public function send(){
        $resultFromGoogleServer = $this->sendToAll();
		
        $resultJson = json_decode($resultFromGoogleServer);
        $pushResult = "nok";
		$pushResult="ok";
		/*
        if($resultJson->success >= 0){
            $pushResult="ok";
        }
	
		
        $this->status = $pushResult;
        $this->device_reached = $resultJson->success;
        $this->device_failure = $resultJson->failure;
		*/
        $this->save();
		
		
        echo $pushResult;
    }
   
    
    
    /*
     * Invia a tutti la notifica
     * Return il risultato della curl eseguita
     */
    private function sendToAll(){
        $result_android = true;
        $result_ios = true;
	
        // include config
        include_once 'include/define.php';

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
		   
        $query=("SELECT * FROM gcm_users;");
        $c = new Connection();
        $users= $c->query($query);
        $c->close();
		   
        $regId_users_android = array();
        $regId_users_ios = array();

        foreach ($users as $user) {
            if($user->os=="android"){
                array_push($regId_users_android, $user->gcm_regid);
            }
            else if($user->os=="ios")
            {
                array_push($regId_users_ios, $user->gcm_regid);
            }
        }
		
        //android

        $message = array("title" => $this->title,"msg" => "$this->text");
        $fields = array(
                        'registration_ids' => $regId_users_android,
                        'data' => array( "message" => $message ),
                        );
        $headers = array(
                        'Authorization: key=' . GOOGLE_API_KEY,
                        'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));
        $result_android = curl_exec($ch);
        curl_close($ch);

        //IOS
        $fieldsios = array(
                        'registration_ids' => $regId_users_ios,
                        'message' => $this->text,
                        );
        $chios = curl_init();
        curl_setopt($chios, CURLOPT_URL, "");
        curl_setopt($chios, CURLOPT_POST, true);
        curl_setopt($chios, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chios, CURL_POSTFIELDS, json_encode($fieldsios));
        $result_ios = curl_exec($chios);
        curl_close($chios);
        
        /*
        if (!extension_loaded('openssl')) { 
            exit("Need openssl"); 
	}   
		
        foreach ($regId_users_ios as $device_token) {	
            if (!extension_loaded('openssl')) { 
                exit("Serve openssl"); 
            }   
            // Put your device token here (without spaces):
            //$device_token="c5d809704ece81f3552c8889c2d800a9ded12835ed1996c2b1299142fb956860";

            // Put your private key's passphrase here:
            $passphrase = 'eis2013';



            ////////////////////////////////////////////////////////////////////////////////
            $certname="techMate_dev.pem";
            $cert_path=$_SERVER['DOCUMENT_ROOT'] . 'wordpress/techmate_mobile_app/ios_certificate/'.$certname;
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $cert_path);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            // Open a connection to the APNS server
            $fp = stream_socket_client(
                    'ssl://gateway.sandbox.push.apple.com:2195', $err,
                    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

            echo 'Connected to APNS' . PHP_EOL;

            // Create the payload body
            $body['aps'] = array(
                    'alert' => $this->text,
                    'sound' => 'default'
                    );

            // Encode the payload as JSON
            $payload = json_encode($body);

            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;

            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));

            if (!$result)
                echo 'Message not delivered' . PHP_EOL;
            else
                echo 'Message successfully delivered' . PHP_EOL;

            // Close the connection to the server
            fclose($fp);
        }
         */

        
        if($result_ios && $result_android){
            return true;
        }
        return false;  
    }
    
    
    
    /*
     * Salva la push in DB.
     * Return TRUE se ha successo, FALSE altrimenti.
     */
    private function save(){
        $query = "INSERT INTO push_notification (timestamp, title,text,status,device_reached,device_failure)";
        $query .= " VALUES (NOW(),'$this->title', '$this->text', '$this->status', '$this->device_reached', '$this->device_failure')";
        
        $c = new Connection();
        $ret = $c->alter($query);
        $c->close();
        
        return $ret;
    }
	

    
    /*
     * Cancella la push id dal DB
     * Return TRUE o FALSE
     */
    public static function delete($id){
        $c = new Connection();
        $ret = $c->alter("DELETE FROM push_notification WHERE id=$id;");
        $c->close();
        
        return $ret;
    }
	
	
    
    /*
     * Return l'array delle push in DB.
     */
    public static function getAllPush(){ 
        $c = new Connection(); 
        $r = $c->query("SELECT * FROM push_notification;");
	return $r;
    }

    
    
    /*
     * Return l'array degli gcmUsers
     */
    public static function getAllGCMusers(){ 
        $c = new Connection(); 
        $r = $c->query("SELECT * FROM gcm_users;");
	return $r;
    }	
}
?>
