<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CourseForm extends Form
{
    public ?int $courseId = null;

    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('required|integer')]
    public $subject_id = 0;

    #[Validate('required|integer')]
    public $teacher_id = 0;

    #[Validate('nullable|string|max:50')]
    public $grade_level = '';

    #[Validate('required|boolean')]
    public $is_published = false;

    public array $subjectOptions = [];
    public array $teacherOptions = [];

    public function mount(): void
    {
        $this->subjectOptions = Subject::where('is_active', true)->pluck('name', 'id')->toArray();
        $this->teacherOptions = User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->pluck('name', 'id')->toArray();
    }

    public function create(): Course
    {
        $this->validate();

        $course = Course::create([
            'title' => $this->title,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'grade_level' => $this->grade_level,
            'is_published' => $this->is_published,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $this->reset();
        return $course;
    }

    public function update(): bool
    {
        $this->validate();

        Course::findOrFail($this->courseId)->update([
            'title' => $this->title,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'grade_level' => $this->grade_level,
            'is_published' => $this->is_published,
            'updated_by' => auth()->id(),
        ]);

        return true;
    }

    public function edit(Course $course): void
    {
        $this->courseId = $course->id;
        $this->title = $course->title;
        $this->description = $course->description ?? '';
        $this->subject_id = $course->subject_id;
        $this->teacher_id = $course->teacher_id;
        $this->grade_level = $course->grade_level ?? '';
        $this->is_published = $course->is_published;
    }
}
