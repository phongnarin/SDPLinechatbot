<?php
// URL API LINE
$API_URL = 'https://api.line.me/v2/bot/message';
// ใส่ Channel access token (long-lived)
$ACCESS_TOKEN = '98NOBfgLUF8OKEXIbyQlCKRZa2iGPmqhhzTpFLGIaOAQCF2gd7ggUmZOu8JHFvYjWSGjf4QHy+VdKJnJD18x03P5zoZiH7ApdtEXeeuON5xAwqgR920jaMUazwBckisJx8M661HzhvU7ubimrau/rgdB04t89/1O/w1cDnyilFU=';
// ใส่ Channel Secret
$CHANNEL_SECRET = '46f9c64c2fa29f2d54e7ba9518244a68';

// Set HEADER
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
// Get request content
$request = file_get_contents('php://input');
// Decode JSON to Array
$request_array = json_decode($request, true);

function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function post_request($subject){
   $data = 'input_data= {
      "request": {
         "subject": '.$subject.',
          "description": "I am unable to fetch mails from the mail server",
          "requester": {
              "id": "4",
              "name": "administrator"
          },
         
          "resolution": {
              "content": "Mail Fetching Server problem has been fixed"
          },
          "status": {
              "name": "Open"
          }
      }
    }';

   $url = "http://csiservicedesk.csigroups.com:8081/api/v3/requests";
   $post_header = array('cache-control: no-cache','Authtoken: 7FAD2C3F-A526-4CE8-B027-AB485B2832BC');
   //Create a request
   $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
     $result = curl_exec($ch);
     curl_close($ch);
     //echo $data ."\n\n" ;
     //echo $result;
   //return $response;
 }

 //Get Request
function get_request($data){
   //OpManager Apikey generate from web
   $apikey = "7FAD2C3F-A526-4CE8-B027-AB485B2832BC" ;
   
   //Get from OpManager
   $curl = curl_init();
   
   curl_setopt_array($curl, array(
      CURLOPT_URL => "http://192.168.115.120:8080/api/v3/requests",
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_TIMEOUT => 30,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "GET",
     CURLOPT_HTTPHEADER => array(
       "cache-control: no-cache","Authtoken: 7FAD2C3F-A526-4CE8-B027-AB485B2832BC"
     ),
     CURLOPT_POSTFIELDS => $data,
   ));
   
   $response = curl_exec($curl);
   
   $err = curl_error($curl);
   curl_close($curl);
   
   //echo $response;
   //return $response;
   
   $result = json_decode($response, true);
   
   print_r($result);
   }


 if ( sizeof($request_array['events']) > 0 ) {
   foreach ($request_array['events'] as $event) {
      
      $reply_message = '';
      $reply_token = $event['replyToken'];
      //$text = $event['message']['text'];
      $text = json_decode($event,true);
      $data = [
         'replyToken' => $reply_token,
         'messages' => [['type' => 'text', 'text' => "Request ".$text." has been created"]]
      ];
      $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
      $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);
      echo "Result: ".$send_result."\r\n";

      //create request
      //post_request($text);

      //get request ID send back to user
      //get_request($data);
    }
}
?>