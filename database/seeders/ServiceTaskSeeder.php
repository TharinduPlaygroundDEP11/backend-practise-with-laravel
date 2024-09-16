<?php

namespace Database\Seeders;

use App\Models\ServiceSection;
use App\Models\ServiceTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $washingSection = ServiceSection::where('name', 'Washing')->first();
        $interiorCleaningSection = ServiceSection::where('name', 'Interior Cleaning')->first();
        $serviceSection = ServiceSection::where('name', 'Service')->first();

        $tasks = [
            ['service_section_id' => $washingSection->id, 'name' => 'Full wash'],
            ['service_section_id' => $washingSection->id, 'name' => 'Body wash'],
            ['service_section_id' => $interiorCleaningSection->id, 'name' => 'Vacuum'],
            ['service_section_id' => $interiorCleaningSection->id, 'name' => 'Shampoo'],
            ['service_section_id' => $serviceSection->id, 'name' => 'Engine oil replacement'],
            ['service_section_id' => $serviceSection->id, 'name' => 'Brake oil replacement'],
            ['service_section_id' => $serviceSection->id, 'name' => 'Coolant replacement'],
            ['service_section_id' => $serviceSection->id, 'name' => 'Air filter replacement'],
            ['service_section_id' => $serviceSection->id, 'name' => 'Oil filter replacement'],
            ['service_section_id' => $serviceSection->id, 'name' => 'AC filter replacement'],
            ['service_section_id' => $serviceSection->id, 'name' => 'Brake shoes replacement'],
        ];

        foreach ($tasks as $task) {
            ServiceTask::create($task);
        }
    }
}
