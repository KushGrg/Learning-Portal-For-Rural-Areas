<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Teacher\ResourceForm;

new #[Layout('components.layouts.app')] #[Title('Resource')] class extends Component {
    public ResourceForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $this->form->edit(\App\Models\Resource::findOrFail($id));
            $this->isEdit = true;
        }
    }

    public function save(): mixed
    {
        if ($this->isEdit) {
            $this->form->update();
        } else {
            $this->form->create();
        }
        return redirect()->route('teacher.resources.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit Resource' : 'Upload Resource' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Title" wire:model.live="form.title" />
            <x-input label="Description" wire:model.live="form.description" />
            <x-input label="Tags" wire:model.live="form.tags" placeholder="comma-separated" />
            <x-select label="File Type" wire:model.live="form.file_type" :options="$form->fileTypeOptions"
                option-value="value" option-label="label" />
            <x-select label="Course" wire:model.live="form.course_id" :options="$form->courseOptions" />
            <x-select label="Topic" wire:model.live="form.topic_id" :options="$form->topicOptions" />

            @if($form->file_type === 'video')
                <x-input label="YouTube URL" wire:model.live="form.youtube_url" placeholder="https://youtube.com/watch?v=..." />
            @else
                <x-file wire:model.live="form.file" accept=".pdf,.jpg,.jpeg,.png" label="Upload File" />
            @endif

            <x-card-footer back-route="{{ route('teacher.resources.index') }}" back-label="Back" button-label="Save" />
        </x-form>
    </x-card>
</div>
