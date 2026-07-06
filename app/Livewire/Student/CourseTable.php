<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class CourseTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    public function enroll(Course $course): void
    {
        auth()->user()->enrollments()->create([
            'course_id' => $course->id,
        ]);
        $this->success('Enrolled successfully!');
    }

    public function unenroll(Course $course): void
    {
        auth()->user()->enrollments()->where('course_id', $course->id)->delete();
        $this->success('Unenrolled.');
    }

    public function render()
    {
        $enrolledIds = auth()->user()->enrollments()->pluck('course_id')->toArray();

        return view('livewire.student.courses.course-table', [
            'courses' => Course::with(['subject', 'teacher'])
                ->where('is_published', true)
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
            'enrolledIds' => $enrolledIds,
        ]);
    }
}
