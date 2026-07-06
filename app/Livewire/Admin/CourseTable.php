<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class CourseTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public ?int $subjectFilter = null;

    public function deleting(Course $course): void
    {
        $course->delete();
        $this->success('Course deleted.');
    }

    public function togglePublish(Course $course): void
    {
        $course->update(['is_published' => !$course->is_published]);
        $this->success($course->is_published ? 'Course published.' : 'Course unpublished.');
    }

    public function render()
    {
        return view('livewire.admin.courses.course-table', [
            'courses' => Course::with(['subject', 'teacher'])
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->when($this->subjectFilter, fn ($q) => $q->where('subject_id', $this->subjectFilter))
                ->latest()
                ->paginate(10),
            'subjects' => \App\Models\Subject::where('is_active', true)->get(),
        ]);
    }
}
