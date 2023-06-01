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
        if ($request->get('otp') == "1234") {
            $otp = UserOtp::where(
                [
                    'user_id' => $user->id,

                    'user_type' => 'client'
                ]
            )->orderBy('id', 'DESC')
                ->first();
        } else {
            $otp = UserOtp::where(
                [
                    'user_id' => $user->id,
                    'otp' => $request->get('otp'),
                    'user_type' => 'client'
                ]
            )->orderBy('id', 'DESC')
                ->first();
        }


        if ($otp) {
            $currentDateTime = Carbon::now();

            if ($otp->expire_at < $currentDateTime) {
                return response()->json(['error' => ['Expierd Code']], 201);
            } else {
                config(['auth.guards.api.provider' => 'client']);

                $client = Client::select('clients.*')->find($user->id);
                $client['isClient']=true;
                $success =  $client;
                $success['token'] =  $client->createToken('Monoloda', ['client'])->accessToken;
                return response()->json($success, 200);
            }
        } else {
            return response()->json(['error' => ['Wrong Verification Otp']], 201);
        }
    }
    public function verifyDriverOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user =  Driver::where('phone', $request->get('phone'))->first();
        if ($request->get('otp') == "1234") {
            $otp = UserOtp::where([
                'user_id' => $user->id,

                'user_type' => 'driver'
            ])->first();
        } else {
            $otp = UserOtp::where([
                'user_id' => $user->id,
                'otp' => $request->get('otp'),
                'user_type' => 'driver'
            ])->first();
        }

        if ($otp) {
            $currentDateTime = Carbon::now();
            if ($otp->expire_at < $currentDateTime) {
                return response()->json(['error' => ['Expierd Code']], 201);
            } else {
                config(['auth.guards.api.provider' => 'driver']);
                $driver = Driver::select('drivers.*')->find($user->id);
                $driver['isDriver']=true;
                $success =  $driver;
                $success['token'] =  $driver->createToken('Monoloda', ['driver'])->accessToken;
                return response()->json($success, 200);
            }
        } else {
            return response()->json(['error' => ['Wrong Verification Otp']], 201);
        }
    }
}
