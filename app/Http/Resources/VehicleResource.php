<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vehicle_id' => $request->id,
            "vehicle_name"=>$request->name,
            "vehicle_model"=>$request->model,
            "vehicle_manufacturer"=>$request->manufacture,
            "vehicle_year"=>$request->vehicle_year,
            'image' => $request->image,
            'vehicle_registration_front' => $request->vehicle_registration_front,
            'vehicle_registration_back' => $request->vehicle_registration_back,
            'driver' => "Driver_name",
            'number_plate' => $request->number_plate,
            'created_at' => (string) $request->created_at,
            'updated_at' => (string) $request->updated_at,
            
          ];
    }
}
