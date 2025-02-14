<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\Attribute;
use App\Models\AttributeValues;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $departments = ['IT', 'Marketing', 'Sales', 'HR'];
        $priorities = ['Low', 'Medium', 'High'];

        $projects = [
            [
                'name' => 'Website Redesign',
                'status' => 'active',
                'attributes' => [
                    'department' => 'IT',
                    'start_date' => '2024-02-01',
                    'end_date' => '2024-05-01',
                    'budget' => '50000',
                    'priority' => 'High'
                ]
            ],
            [
                'name' => 'Marketing Campaign',
                'status' => 'pending',
                'attributes' => [
                    'department' => 'Marketing',
                    'start_date' => '2024-03-01',
                    'end_date' => '2024-06-01',
                    'budget' => '30000',
                    'priority' => 'Medium'
                ]
            ],
            [
                'name' => 'Sales Training',
                'status' => 'completed',
                'attributes' => [
                    'department' => 'Sales',
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-01-31',
                    'budget' => '15000',
                    'priority' => 'Low'
                ]
            ]
        ];

        foreach ($projects as $projectData) {
            $project = Project::create([
                'name' => $projectData['name'],
                'status' => $projectData['status']
            ]);

            // Attach random users (1-3) to each project
            $project->users()->attach(
                $users->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Add attribute values
            foreach ($projectData['attributes'] as $attributeName => $value) {
                $attribute = Attribute::where('name', $attributeName)->first();
                if ($attribute) {
                    AttributeValues::create([
                        'attribute_id' => $attribute->id,
                        'entity_id' => $project->id,
                        'value' => $value
                    ]);
                }
            }
        }
    }
}
