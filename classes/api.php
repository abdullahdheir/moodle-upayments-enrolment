<?php
namespace enrol_upayment;
defined('MOODLE_INTERNAL') || die();
class api {
    private $merchant,$key,$secret,$sandbox;
    public function __construct(){
        $this->merchant=get_config('enrol_upayment','merchantid');
        $this->key=get_config('enrol_upayment','apikey');
        $this->secret=get_config('enrol_upayment','apisecret');
        $this->sandbox=get_config('enrol_upayment','sandbox');
    }
    private function endpoint(){
        return $this->sandbox?'https://sandbox.upayments.com/api/invoice':'https://api.upayments.com/invoice';
    }
    public function create_invoice($user,$instance,$amount,$callback){
        $payload=[
            'merchant_id'=>$this->merchant,
            'username'=>$user->firstname.' '.$user->lastname,
            'email'=>$user->email,
            'phone'=>preg_replace('/[^0-9]/','',$user->phone1),
            'amount'=>$amount,
            'currency'=>$instance->currency,
            'order_number'=>uniqid('mdl'),
            'redirect_url'=>$callback,
            'api_key'=>$this->key,
        ];
        $payload['signature']=hash_hmac('sha256',json_encode($payload),$this->secret);
        $curl=new \curl();
        $resp=$curl->post($this->endpoint(),$payload);
        $json=json_decode($resp);
        if(!empty($json->payment_url)){
            return $json->payment_url;
        }
        throw new \moodle_exception('Could not obtain payment URL');
    }
    public function verify($trackid){
        $url=($this->sandbox?'https://sandbox':'https://api').".upayments.com/invoice/".$trackid."?merchant_id=".$this->merchant."&api_key=".$this->key;
        $resp=(new \curl())->get($url);
        $json=json_decode($resp);
        return isset($json->result)&&$json->result==='CAPTURED';
    }
}
