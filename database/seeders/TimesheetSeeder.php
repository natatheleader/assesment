<?php

namespace Database\Seeders;

use App\Models\Timesheet;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TimesheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::with('users')->get();
        $startDate = Carbon::now()->subDays(30);

        foreach ($projects as $project) {
            foreach ($project->users as $user) {
                // Create 10 random timesheet entries for each user-project combination
                for ($i = 0; $i < 10; $i++) {
                    Timesheet::create([
                        'user_id' => $user->id,
                        'project_id' => $project->id,
                        'task_name' => $this->getRandomTask($project->name),
                        'date' => $startDate->copy()->addDays(rand(1, 30))->format('Y-m-d'),
                        'hour' => rand(1, 8)
                    ]);
                }
            }
        }
    }

    private function getRandomTask($projectName)
    {
        $tasks = [
            'Planning',
            'Development',
            'Testing',
            'Documentation',
            'Meeting',
            'Code Review',
            'Bug Fixing',
            'Design'
        ];

        return $projectName . ' - ' . $tasks[array_rand($tasks)];
    }
}
