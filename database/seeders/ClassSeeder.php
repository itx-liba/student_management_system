<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'] as $name) {
            StudentClass::firstOrCreate(['name' => $name]);
        }
    }
}