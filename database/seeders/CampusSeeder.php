<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = [
            ['name' => 'University of Dhaka', 'type' => 'university', 'district' => 'Dhaka', 'location' => 'Shahbagh, Dhaka'],
            ['name' => 'Bangladesh University of Engineering and Technology', 'type' => 'university', 'district' => 'Dhaka', 'location' => 'Palashi, Dhaka'],
            ['name' => 'Dhaka College', 'type' => 'college', 'district' => 'Dhaka', 'location' => 'Dhanmondi, Dhaka'],
            ['name' => 'Notre Dame College', 'type' => 'college', 'district' => 'Dhaka', 'location' => 'Motijheel, Dhaka'],
            ['name' => 'Chittagong University of Engineering & Technology', 'type' => 'university', 'district' => 'Chattogram', 'location' => 'Raozan, Chattogram'],
            ['name' => 'University of Rajshahi', 'type' => 'university', 'district' => 'Rajshahi', 'location' => 'Kazla, Rajshahi'],
            ['name' => 'Khulna University', 'type' => 'university', 'district' => 'Khulna', 'location' => 'Gollamari, Khulna'],
            ['name' => 'Shahjalal University of Science and Technology', 'type' => 'university', 'district' => 'Sylhet', 'location' => 'Kumargaon, Sylhet'],
            ['name' => 'Rajshahi College', 'type' => 'college', 'district' => 'Rajshahi', 'location' => 'Rajshahi City'],
            ['name' => 'Government Titumir College', 'type' => 'college', 'district' => 'Dhaka', 'location' => 'Mohakhali, Dhaka'],
        ];

        foreach ($campuses as $campus) {
            Campus::updateOrCreate(
                ['name' => $campus['name']],
                $campus + ['is_active' => true],
            );
        }
    }
}
