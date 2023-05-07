<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClientResource;
use App\Http\Resources\DriverResource;
use App\Models\Client;
use App\Models\Driver;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use League\CommonMark\Node\Block\Document;

class RegisterController extends BaseController
{
    //

    public function driverRegister(Request $request)
    {

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'drives_license_front' => 'required|mimes:png,jpg,jpeg,gif|max:2048',
                'drives_license_back' => 'required|mimes:png,jpg,jpeg,gif|max:2048',
                'identity_document' => 'required|mimes:png,jpg,jpeg,gif|max:2048',
                'image' => 'required|mimes:png,jpg,jpeg,gif|max:2048',


            ]);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->errors()->all()]);
            }

            /**
             * drives_license_front
             *drives_license_back
             */
            $documents = [
                'drives_license_front' => $request->file('drives_license_front'),
                'drives_license_back' => $request->file('drives_license_back'),
                'identity_document' => $request->file('identity_document'),
                'image' => $request->file('image'),
            ];
$i=0;
            foreach ($documents as  $key => $value ) {
                if ($file =$value) {
                    $path = $file->store('public/files/drivers/' . $request['firstname'] . $request['lastname'] . '/');
                    $name = $file->getClientOriginalName();
                    //store your file into directory and db
                    $data[$key] = $path . $name;
                }
                print(++$i);
            }


            $chkephone =  Driver::where('phone', $request->get('phone'))->first();

            if (!empty($chkephone)) {
                $response = "phone number is exist";
                return $this->sendError($response);
            } else {

                $data = $request->all();

                $data['password'] = Hash::make($request->password);

                $driver = Driver::create($data);

                $licence = $driver->driverLicense()->create([
                    'drives_license_number' => $data['drives_license_number'],
                    'drives_license_front' => $data['drives_license_front'],
                    'drives_license_back' => $data['drives_license_back'],
                    'expiration_date' => $data['expiration_date'],
                    'driver_id' => $driver->id,
                ]);

                $address = $driver->address()->create([
                    'driver_id' =>  $driver->id,
                    'govermant' => $data['govermant'],
                    'street' => $data['street'],
                    'housenumber' => $data['housenumber'],
                    'type' => $data['type'],
                ]);
                $token = $driver->createToken('Monoloda', ['driver'])->accessToken;

                $response['driver'] = [
                    'user' => new DriverResource($driver),
                    'token' => $token
                ];
                DB::commit();
                $msg = 'User register successfully.';
            }
            return $this->sendResponse($response, $msg);
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }

    public function clientRegister(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->errors()->all()]);
            }

            $chkephone =  Client::where('phone', $request->get('phone'))->first();

            if (!empty($chkephone)) {
                $response = "phone number is exist";
                return $this->sendError($response);
            } else {
                $data = $request->all();

                $data['password'] = Hash::make($request->password);

                $client = Client::create($data);

                $token = $client->createToken('Monoloda', ['client'])->accessToken;

                $response['Client'] = [
                    'user' => new ClientResource($client),
                    'token' => $token
                ];
                DB::commit();
                $msg = 'User register successfully.';
            }
            return $this->sendResponse($response, $msg);
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }
}
