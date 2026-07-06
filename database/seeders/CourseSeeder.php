<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) return;

        $subjects = Subject::all();
        if ($subjects->isEmpty()) return;

        $courses = [
            ['title' => 'Basic Mathematics', 'subject' => 'Mathematics', 'grade_level' => 'Grade 6', 'description' => 'Introduction to basic mathematical concepts'],
            ['title' => 'Introduction to Physics', 'subject' => 'Science', 'grade_level' => 'Grade 8', 'description' => 'Basic physics concepts for beginners'],
            ['title' => 'English Grammar Fundamentals', 'subject' => 'English', 'grade_level' => 'Grade 7', 'description' => 'Essential English grammar rules'],
        ];

        foreach ($courses as $courseData) {
            $subject = $subjects->firstWhere('name', $courseData['subject']);
            if (!$subject) continue;

            Course::create([
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'grade_level' => $courseData['grade_level'],
                'is_published' => true,
                'created_by' => $teacher->id,
                'updated_by' => $teacher->id,
            ]);
        }
    }
}
