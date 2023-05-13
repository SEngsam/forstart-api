<?php

namespace App\Http\Controllers\Api;

use App\User;

use App\Models\Client;
use App\Models\Driver;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;





use Hash;



class OtpController extends BaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
    }

    public  function sendOtp(int $phone)
    {
    }

    public function verifyClientOtp(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user =  Client::where('phone', $request->get('phone'))->first();

        if ($user) {
            config(['auth.guards.api.provider' => 'client']);

            //send otp 
            $currentDateTime = Carbon::now();
            $expire_at = Carbon::now()->addMinute(30);
            $otp= mt_rand(1111,9999);
 
            $data = array(
                'user_id' => $user->id,
                'user_type' => 'client',
                'otp' =>  $otp ,
                'expire_at' => $expire_at,
            );
 
            $success['token'] =  $user->createToken('Monoloda',['client'])->accessToken; 
            $user_otp = UserOtp::create($data);

            $success['success']=$user_otp->sendSMS($user->id,$user->phone);


            return response()->json($success, 200);
        } else {
            return response()->json(['error' => ['Phone number is Wrong.']], 200);
        }
    }
    public function verifyDriverOtp(Request $request)
    {

        try {
            DB::beginTransaction();

            // verfiy Otp 
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'otp' => 'required',
            ]);
            $user_id = Driver::where(
                'phone',
                $request->get('phone'),

            )->first()->id;
            $otp = UserOtp::where(
                [
                    'user_id' => $user_id,
                    'otp' => $request->get('otp'),
                    'user_type' => 'driver'
                ]
            )->first();

            if ($otp) {


                $currentDateTime = Carbon::now();

                if ($otp->expire_at < $currentDateTime) {
                    return response()->json(['error' => ['Expierd Code']], 201);
                } else {
                    config(['auth.guards.api.provider' => 'driver']);

                    $driver = Driver::select('drivers.*')->find($user_id);
                    $success =  $driver;
                    $success['token'] =  $driver->createToken('Monoloda', ['driver'])->accessToken;
                    return response()->json($success, 200);
                }
            } else {
                return response()->json(['error' => ['Wrong Verification Otp']], 201);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }
}
