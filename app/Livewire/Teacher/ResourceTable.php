<?php

namespace App\Livewire\Teacher;

use App\Models\Resource;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ResourceTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public string $typeFilter = '';

    protected FileUploadService $fileService;

    public function boot(FileUploadService $fileService): void
    {
        $this->fileService = $fileService;
    }

    public function deleting(Resource $resource): void
    {
        $file = $resource->primaryFile;
        if ($file) {
            $this->fileService->deleteResourceFile($file);
        }
        $resource->delete();
        $this->success('Resource deleted.');
    }

    public function render()
    {
        return view('livewire.teacher.resources.resource-table', [
            'resources' => Resource::with(['course', 'topic', 'files'])
                ->where('teacher_id', auth()->id())
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->when($this->typeFilter, fn ($q) => $q->where('file_type', $this->typeFilter))
                ->latest()
                ->paginate(10),
        ]);
    }
}
