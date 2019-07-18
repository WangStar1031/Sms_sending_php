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
        $creds = array();
        if (SMS_TEST_MODE) {
            $creds['SID'] = SMS_SID_TEST;
            $creds['token'] = SMS_TOKEN_TEST;
        } else {
           $creds['SID'] = "acc8adaa3bd-ac08-3ead-b3ff-4d9e03abe2b0";
          $creds['token'] = "aut22c33f64-4b28-3e71-9f71-1944fb9f435a";
        }
        $twilio_number = "+15017122661";
        $client = new Client($creds['SID'], $creds['token']);
        $response = array();
        try {
            $to = str_replace(" ", '', $to);
            $tolst = explode(",", $to);
            foreach ($tolst as $key => $tonumber) {
                $client->messages->create(
                $tonumber,
                array(
                    'from' => $from,
                    'body' => $message,
                )
              );    
               sleep(0.2);
            }
            $response['success'] = true;
            $response['message'] = 'Message was sent to number: ' . $to . ' successfully';
        } catch (Exception $err) {
            $response['success'] = false;
            $response['message'] = $err->getMessage();
        }
        return $response;
    }
    public function send()
    {
        if (!isset($_POST['submit'])) {
            return;
        }
        echo json_encode($this->send_SMS($_POST['to'], $_POST['from'], $_POST['message']));
    }

}
