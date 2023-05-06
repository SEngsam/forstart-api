<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Driver;
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

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'user']);
            
            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            $success['token'] =  $user->createToken('MyApp',['user'])->accessToken; 

            return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Email and Password are Wrong.']], 200);
        }
    }

    public function driverLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('driver')->attempt(['phone' => request('phone'), 'password' => request('password')])){
 
            config(['auth.guards.api.provider' => 'driver']);
            
            $driver = Driver::select('drivers.*')->find(auth()->guard('driver')->user()->id);
            $success =  $driver;
            $success['token'] =  $driver->createToken('Monoloda',['driver'])->accessToken; 

            return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Phone number and Password are Wrong.']], 200);
        }
    }

    public function clientLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('client')->attempt(['phone' => request('phone'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'client']);
            
            $client = client::select('clients.*')->find(auth()->guard('client')->user()->id);
            $success =  $client;
            $success['token'] =  $client->createToken('Monoloda',['client'])->accessToken; 

            return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Phone number and Password are Wrong.']], 200);
        }
    }

    
}
