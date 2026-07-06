<?php

namespace App\Livewire\Student;

use App\Models\Resource;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class LibraryTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public string $typeFilter = '';
    public ?int $courseFilter = null;
    public ?int $teacherFilter = null;

    public function toggleBookmark(int $resourceId): void
    {
        $user = auth()->user();
        $existing = $user->bookmarks()->where('resource_id', $resourceId)->first();

        if ($existing) {
            $existing->delete();
            $this->success('Bookmark removed.');
        } else {
            $user->bookmarks()->create(['resource_id' => $resourceId]);
            $this->success('Resource bookmarked.');
        }
    }

    public function render()
    {
        $user = auth()->user();
        $bookmarkedIds = $user->bookmarks()->pluck('resource_id')->toArray();

        return view('livewire.student.library.library-table', [
            'resources' => Resource::with(['course', 'teacher', 'files'])
                ->where('is_active', true)
                ->whereHas('course', fn ($q) => $q->where('is_published', true))
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")->orWhere('tags', 'like', "%{$this->search}%"))
                ->when($this->typeFilter, fn ($q) => $q->where('file_type', $this->typeFilter))
                ->when($this->courseFilter, fn ($q) => $q->where('course_id', $this->courseFilter))
                ->when($this->teacherFilter, fn ($q) => $q->where('teacher_id', $this->teacherFilter))
                ->latest()
                ->paginate(12),
            'courses' => \App\Models\Course::where('is_published', true)->get(),
            'teachers' => \App\Models\User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->get(),
            'bookmarkedIds' => $bookmarkedIds,
        ]);
    }
}
