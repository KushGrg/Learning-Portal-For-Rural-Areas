<?php

namespace App\Livewire\Teacher;

use App\Enums\FileType;
use App\Models\Course;
use App\Models\Resource;
use App\Models\Topic;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResourceForm extends Form
{
    public ?int $resourceId = null;

    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('nullable|string|max:500')]
    public $tags = '';

    #[Validate('required|in:pdf,video,image')]
    public $file_type = 'pdf';

    #[Validate('required|integer')]
    public $course_id = 0;

    #[Validate('nullable|integer')]
    public $topic_id = 0;

    #[Validate('nullable|file|max:51200')]
    public $file;

    #[Validate('nullable|url')]
    public $youtube_url = '';

    public array $courseOptions = [];
    public array $topicOptions = [];
    public array $fileTypeOptions = [];

    public function mount(): void
    {
        $this->fileTypeOptions = collect(FileType::getInstances())
            ->map(fn ($ft) => ['value' => $ft->value, 'label' => FileType::labels()[$ft->value]])
            ->toArray();

        $this->loadCourses();
    }

    public function loadCourses(): void
    {
        $this->courseOptions = Course::where('is_published', true)
            ->when(!auth()->user()->hasRole('admin'), fn ($q) => $q->where('teacher_id', auth()->id()))
            ->pluck('title', 'id')
            ->toArray();
    }

    public function updatedCourseId(): void
    {
        $this->topicOptions = Topic::where('course_id', $this->course_id)
            ->pluck('title', 'id')
            ->toArray();
        $this->topic_id = 0;
    }

    public function create(): Resource
    {
        $this->validate();

        $resource = Resource::create([
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'file_type' => $this->file_type,
            'course_id' => $this->course_id,
            'topic_id' => $this->topic_id ?: null,
            'teacher_id' => auth()->id(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        if ($this->file_type === 'video' && $this->youtube_url) {
            $resource->files()->create([
                'original_name' => 'YouTube Video',
                'stored_name' => 'youtube',
                'path' => '',
                'mime_type' => 'video/youtube',
                'size' => 0,
                'youtube_url' => $this->youtube_url,
            ]);
        } elseif ($this->file) {
            app(\App\Services\FileUploadService::class)->uploadResourceFile($this->file, $resource->id);
        }

        $this->reset();
        return $resource;
    }

    public function update(): bool
    {
        $this->validate();

        $resource = Resource::findOrFail($this->resourceId);
        $resource->update([
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'file_type' => $this->file_type,
            'course_id' => $this->course_id,
            'topic_id' => $this->topic_id ?: null,
            'updated_by' => auth()->id(),
        ]);

        if ($this->file) {
            $existingFile = $resource->primaryFile;
            if ($existingFile) {
                app(\App\Services\FileUploadService::class)->deleteResourceFile($existingFile);
            }
            app(\App\Services\FileUploadService::class)->uploadResourceFile($this->file, $resource->id);
        }

        return true;
    }

    public function edit(Resource $resource): void
    {
        $this->resourceId = $resource->id;
        $this->title = $resource->title;
        $this->description = $resource->description ?? '';
        $this->tags = $resource->tags ?? '';
        $this->file_type = $resource->file_type;
        $this->course_id = $resource->course_id;
        $this->topic_id = $resource->topic_id ?? 0;
        $this->youtube_url = $resource->primaryFile?->youtube_url ?? '';

        $this->updatedCourseId();
    }
}
