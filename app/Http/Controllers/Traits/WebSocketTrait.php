<?php
 namespace App\Http\Controllers\Traits;
 use Ixudra\Curl\Facades\Curl;
 trait WebSocketTrait
 {
     protected function sendWSMessage($to_client, $from_client, $data) {
        $message = [
            "to_client"   => $to_client,
            "from_client" => $from_client,
            "data"        => json_encode($data),
            "type"        => 'message'
        ];
        $send = Curl::to("http://localhost:3000/messages/send")->withData($message)->asJson()->post();
        if($send && $send->status) {
            return true;
        }
        return false;
     }
 }
