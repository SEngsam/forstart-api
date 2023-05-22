<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\BaseController;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Models\TripType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends BaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function __construct()
    {
        $this->middleware('auth:client-api');
    }


    public function tripType()
    {
        try {

            DB::beginTransaction();
            $client = auth()->guard('client-api')->user();

            $trips = TripType::select('id','name')->where('local', 'en')->get()->all();
            foreach ($trips as $trip){
                $response['Trips'][]=  new TripResource($trip);

            }
            $msg = 'Success';

            return $this->sendResponse($response, $msg);
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }
    public function findDriver(Request $request)
    {
        try {
 
            DB::beginTransaction();
            $client = auth()->guard('client-api')->user();

            $full_name = $client->firstname . ' ' . $client->lastname;
            $data =  $request->all();
            $data['client_id']=$client->id;
            $data['driver_id']=0;
            $order = Trip::create($data);
            $response['Order'] =  new TripResource($order);
            $msg = 'Order submit successfully.';

            return $this->sendResponse($response, $msg);
        
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }
}
