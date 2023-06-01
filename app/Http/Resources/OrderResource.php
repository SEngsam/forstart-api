<?php

namespace App\Http\Resources;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $trip = array(
            'id' => $this->id,
            'trip_type_id' => $this->trip_type_id,
            'trip_status_id' => $this->trip_status_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'from' => $this->from,
            'to' => $this->to,
            'comment' => $this->comment,
        );

        if ($this->trip_type_id == 1) {
            $trip['trip_type'] = "City to City";
        } else {
            $trip['trip_type'] = "Ride";
        }
        if ($this->trip_status_id == 0) {
            $trip['trip_status'] = "pindding";
        } elseif ($this->trip_status_id == 1) {
            $trip['trip_status'] = "Approverd";
        } else {
            $trip['trip_status'] = "Cancelled";
        }
 
        if($this->driver_id!=0){
            $driver=Driver::where('id',$this->driver_id)->get()->first();
            $trip['deiver_id']=$this->driver_id;
            $trip['deiver_name']=$driver['firstname']+' '+$driver['lastname'];
        }
 
        return $trip;
    }
}
