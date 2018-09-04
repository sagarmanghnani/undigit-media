<?php

class GCM {

    //put your code here
    // constructor
    function __construct() {

    }

    /**
     * Sending Push Notification
     */
    public function send_notification($registatoin_ids, $message) {

	   $url = 'https://fcm.googleapis.com/fcm/send';
       $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=AAAAZh_J-pY:APA91bEUiOOGErZv1Z1o1MPVjC73ac1xYjrFaKCjf80XEpIqzhqGMOOe39CgZJGVUadxH3dngjBakZGqlkjH_L9Q9hBRuJvnhxyxoUJt5mvxDwVpdbAKNGv5jMy4hHF9LWxGlxm-xPD_8tZT8pa3rgrReRbunCLgrA',
            'Content-Type:application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
		if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
//        echo $result;
    }

}

?>
