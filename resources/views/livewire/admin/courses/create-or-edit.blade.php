<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Admin\CourseForm;

new #[Layout('components.layouts.app')] #[Title('Course')] class extends Component {
    public CourseForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $this->form->edit(\App\Models\Course::findOrFail($id));
            $this->isEdit = true;
        }
    }

    public function save(): mixed
    {
        if ($this->isEdit) {
            $this->form->update();
            $this->success('Course updated.');
        } else {
            $this->form->create();
            $this->success('Course created.');
        }
        return redirect()->route('admin.courses.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit Course' : 'Create Course' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Title" wire:model.live="form.title" />
            <x-input label="Description" wire:model.live="form.description" />
            <x-select label="Subject" wire:model.live="form.subject_id" :options="$form->subjectOptions" />
            <x-select label="Teacher" wire:model.live="form.teacher_id" :options="$form->teacherOptions" />
            <x-input label="Grade Level" wire:model.live="form.grade_level" placeholder="e.g. Grade 8" />
            <x-toggle label="Published" wire:model.live="form.is_published" />

            <x-card-footer back-route="{{ route('admin.courses.index') }}" back-label="Back" button-label="Save" />
        </x-form>
    </x-card>
</div>
