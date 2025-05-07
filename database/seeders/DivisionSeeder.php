<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            ['name' => 'IT'],
            ['name' => 'Marketing'],
            ['name' => 'Finance'],
            ['name' => 'HR'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
