<?php

namespace Database\Seeders;

use App\Models\ServiceSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['name' => 'Washing'],
            ['name' => 'Interior Cleaning'],
            ['name' => 'Service'],
        ];

        foreach ($sections as $section) {
            ServiceSection::create($section);
        }
    }
}
