<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            'IT Development',
            'Marketing',
            'Finance',
            'Human Resources',
            'Operations'
        ];

        foreach ($divisions as $division) {
            Division::create(['name' => $division]);
        }
    }
}
