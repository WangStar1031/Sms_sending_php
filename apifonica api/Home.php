<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/libraries/sms/Twilio/autoload.php';

use Twilio\Rest\Client;

class Home extends CI_Controller
{

    public function index()
    {

        $this->load->view('home');
    }
    public function send_SMS($to, $from, $message)
    {
        try {
            $sid = "acc8adaa3bd-ac08-3ead-b3ff-4d9e03abe2b0";
            $authToken = "aut22c33f64-4b28-3e71-9f71-1944fb9f435a";
            $url = "https://api.apifonica.com/v2/accounts/" . $sid . "/messages";
            $from = "3197010280752";

            $to = str_replace(" ", '', $to);
            $tolst = explode(",", $to);
            foreach ($tolst as $key => $tonumber) {
                $ch = curl_init();
                $fields = array(
                    'from' => $from,
                    'to' => $tonumber,
                    'text' => $message
                );

                //url-ify the data for the POST
                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                rtrim($fields_string, '&');

                curl_setopt($ch, CURLOPT_URL, '–X');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $authToken);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $server_output = curl_exec($ch);

                curl_close ($ch);
                sleep(0.2);
            }
            $response['success'] = true;
            $response['message'] = 'Message was sent to number: ' . $to . ' successfully';
        } catch( Exception $err){
            $response['success'] = false;
            $response['message'] = $err->getMessage();
        }

    }
    public function send()
    {
        if (!isset($_POST['submit'])) {
            return;
        }
        echo json_encode($this->send_SMS($_POST['to'], $_POST['from'], $_POST['message']));
    }

}
