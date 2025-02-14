<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'department',
                'type' => 'text'
            ],
            [
                'name' => 'start_date',
                'type' => 'date'
            ],
            [
                'name' => 'end_date',
                'type' => 'date'
            ],
            [
                'name' => 'budget',
                'type' => 'number'
            ],
            [
                'name' => 'priority',
                'type' => 'select'
            ]
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
