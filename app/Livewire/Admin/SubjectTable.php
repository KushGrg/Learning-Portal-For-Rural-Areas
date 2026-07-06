<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class SubjectTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    public function deleting(Subject $subject): void
    {
        $subject->delete();
        $this->success('Subject deleted.');
    }

    public function render()
    {
        return view('livewire.admin.subjects.subject-table', [
            'subjects' => Subject::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
        ]);
    }
}
