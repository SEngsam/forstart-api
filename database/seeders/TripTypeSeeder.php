<?php

namespace Database\Seeders;

use App\Models\TripType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'local' => 'en',
                'name' => 'city to city',
            ],  
            [
                'local' => 'en',
                'name' => 'ride',

            ],
            [
                'local' => 'ar',
                'name' => 'مدينة لمدينة',
            ],  
            [
                'local' => 'ar',
                'name' => 'جولة',

            ],  
        ];
        foreach ($data as $row) {
            TripType::create($row);
        }
    }
}
