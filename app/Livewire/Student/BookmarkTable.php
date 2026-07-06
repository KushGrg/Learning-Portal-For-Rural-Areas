<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class BookmarkTable extends Component
{
    use WithPagination, Toast;

    public function removeBookmark(int $resourceId): void
    {
        auth()->user()->bookmarks()->where('resource_id', $resourceId)->delete();
        $this->success('Bookmark removed.');
    }

    public function render()
    {
        return view('livewire.student.bookmarks.bookmark-table', [
            'bookmarks' => auth()->user()->bookmarks()
                ->with(['resource.course', 'resource.teacher'])
                ->latest()
                ->paginate(10),
        ]);
    }
}
