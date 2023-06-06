<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\BaseController;
use App\Http\Resources\OrderResource;
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

            $trips = TripType::select('id', 'name')->where('local', 'en')->get()->all();

            foreach ($trips as $trip) {
                $response['Trips'][] =  new TripResource($trip);
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


            $data = array(
                'full_name' => $client->firstname . ' ' . $client->lastname,
                'client_id' => $client->id,
                'driver_id' => 0,
                'trip_status_id' =>  0,
                'trip_type_id' =>  0,
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'comment' => $request->input('comment'),
                'offer_far' => $request->input('offer_far'),

            );
            $order = Trip::create($data);

            $response['Order'] =  new TripResource($order);
            DB::commit();
            $msg = 'Order submit successfully.';
            return $this->sendResponse($response, $msg);
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError(__('auth.some_error'), $this->exMessage($e));
        }
    }

    public function orders(Request $request)
    {




        $trip_status_id = $request->get('order_type');
        DB::beginTransaction();
        $client = auth()->guard('client-api')->user();

        if ($request->get('order_type') != null) {
            $orders =  Trip::where(['client_id' => $client->id, 'trip_status_id' => $trip_status_id])->get()->all();
        } else {
            $orders = Trip::get()->all();
        }

        foreach ($orders as $order) {
            if ($order['trip_status_id'] == 1) {
                $oreder['trip_status'] = "Cancelled";
            } elseif (
                $order['trip_status_id'] == 2
            ) {
                $oreder['trip_status'] = "approverd";
            } else {
                $oreder['trip_status'] = "pinding";
            }
            $response['Orders'][] =  new OrderResource($order);
        }
        // $response['Orders'] =  new TripResource($order);
        DB::commit();
        $msg = 'Order submit successfully.';
        return $this->sendResponse($response, $msg);
    }
}
