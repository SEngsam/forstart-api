<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VehicleController extends BaseController
{
    //
    //update certificate of vehicle registration

    public function __construct()
    {
        $this->middleware('auth:driver-api');
    }


    public function updateVehicleRegistration(Request $request)
    {

        try {
            $driver = auth()->guard('driver-api')->user();
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'vehicle_registration_front' => 'required',
                'vehicle_registration_back' => 'required',
                'number_plate' => 'required',
                'image' => 'required',

            ]);

            if ($validator->fails()) {
                return $this->sendError('Unauthorised.', ['error' => $validator->errors()->all()]);
            }
            $data = $request->all();
            $data['driver_id'] = $driver->id;

            $has_vehicle =  Vehicle::where('driver_id', $driver->id)->get()->first();

            $documents = [
                'drives_license_front' => $request->file('drives_license_front'),
                'drives_license_back' => $request->file('drives_license_back'),
                'identity_document' => $request->file('identity_document'),
                'image' => $request->file('image'),
            ];

            foreach ($documents as  $key => $value) {
                if ($file = $value) {
                    $path = $file->store('public/files/drivers/' . $driver->firstname . $driver->lastname . '/');
                    $name = $file->getClientOriginalName();
                    //store your file into directory and db
                    $data[$key] = $path . $name;
                }
            }
            if (!$has_vehicle) {

                //update
                $vehicle = Vehicle::create($data);
                $response['Vehicle'] = [
                    'Vehicle' => new VehicleResource($vehicle),
                ];
                DB::commit();
            } else {

                DB::table('vehicles')->where('driver_id', $driver->id)->update([
                    'vehicle_registration_front' => $data['vehicle_registration_front'],
                    'vehicle_registration_back' => $data['vehicle_registration_back'],
                    'number_plate' => $data['number_plate'],
                    'image' => $data['image'],
                ]);
                $response['Vehicle'] = [
                    'Vehicle' => new VehicleResource($data),
                ];
            }
            $msg = 'Vehicle update successfully.';

            return $this->sendResponse($response, $msg);
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }
}
