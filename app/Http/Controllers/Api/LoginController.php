<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Driver;
use App\Models\UserOtp;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LoginController extends BaseController
{
    public function __construct()
    {
    }
    public function userDashboard()
    {
        $users = User::all();
        $success =  $users;

        return response()->json($success, 200);
    }

    public function clientDashboard()
    {
       
        $users = Client::all();
        $success =  $users;

        return response()->json($success, 200);
    }

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phome' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'user']);

            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            if ($user->is_verified) {
                $success['token'] =  $user->createToken('MyApp', ['user'])->accessToken;
            }

            return response()->json($success, 200);
        } else {
            return response()->json(['error' => ['Email and Password are Wrong.']], 200);
        }
    }

    public function driverLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user =  Driver::where('phone', $request->get('phone'))->first();

        if ($user) {
            config(['auth.guards.api.provider' => 'driver']);

            //send otp 
            $expire_at = Carbon::now()->addMinute(30);
            $otp= mt_rand(1111,9999);
 
            $data = array(
                'user_id' => $user->id,
                'user_type' => 'driver',
                'otp' =>  $otp ,
                'expire_at' => $expire_at,
            );
            $user_otp = UserOtp::create($data);
            $success['success']=$user_otp->sendSMS($user->id,$user->phone);
            return response()->json($success, 200);
        } else {
            return response()->json(['error' => ['Phone number is Wrong.']], 200);
        }
    }

    public function clientLogin(Request $request)
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
            
            $expire_at = Carbon::now()->addMinute(30);
            $otp= mt_rand(1111,9999);
 
            $data = array(
                'user_id' => $user->id,
                'user_type' => 'client',
                'otp' =>  $otp ,
                'expire_at' => $expire_at,
            );
 
            $user_otp = UserOtp::create($data);
            $success['success']=$user_otp->sendSMS($user->id,$user->phone);
            return response()->json($success, 200);
        } else {
            return response()->json(['error' => ['Phone number is Wrong.']], 200);
        }
    }

    
}
