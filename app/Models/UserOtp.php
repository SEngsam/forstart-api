<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'user_type','otp', 'expire_at'];

    public function sendSMS($id,$receiverNumber)
    {
        $message = "Login OTP is ".$this->otp;
    
        try {
  
            // $account_sid = getenv("TWILIO_SID");
            // $auth_token = getenv("TWILIO_TOKEN");
            // $twilio_number = getenv("TWILIO_FROM");
  
            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create($receiverNumber, [
            //     'from' => $twilio_number, 
            //     'body' => $message]);
   
            return 'SMS Sent Successfully.';
    
        } catch (Exception $e) {
            return ("Error: ". $e->getMessage());
        }
    }

    // public function verifySMS($id,$receiverNumber)
    // {
    //     $message = "Login OTP is ".$this->otp;
    
    //     try {
  
    //         // $account_sid = getenv("TWILIO_SID");
    //         // $auth_token = getenv("TWILIO_TOKEN");
    //         // $twilio_number = getenv("TWILIO_FROM");
  
    //         // $client = new Client($account_sid, $auth_token);
    //         // $client->messages->create($receiverNumber, [
    //         //     'from' => $twilio_number, 
    //         //     'body' => $message]);
   
    //         return 'SMS Sent Successfully.';
    
    //     } catch (Exception $e) {
    //         info("Error: ". $e->getMessage());
    //     }
    // }

}