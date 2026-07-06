<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) return;

        $subjects = [
            ['name' => 'Mathematics', 'description' => 'Mathematical concepts and problem solving'],
            ['name' => 'Science', 'description' => 'Physics, Chemistry, and Biology'],
            ['name' => 'English', 'description' => 'English language and literature'],
            ['name' => 'Nepali', 'description' => 'Nepali language and literature'],
            ['name' => 'Social Studies', 'description' => 'History, Geography, and Civics'],
            ['name' => 'Computer Science', 'description' => 'Computer fundamentals and programming'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                ...$subject,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }
    }
}
