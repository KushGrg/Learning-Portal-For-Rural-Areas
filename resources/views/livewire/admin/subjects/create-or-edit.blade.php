<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Admin\SubjectForm;

new #[Layout('components.layouts.app')] #[Title('Subject')] class extends Component {
    public SubjectForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $this->form->edit(\App\Models\Subject::findOrFail($id));
            $this->isEdit = true;
        }
    }

    public function save(): mixed
    {
        if ($this->isEdit) {
            $this->form->update();
            $this->success('Subject updated.');
        } else {
            $this->form->create();
            $this->success('Subject created.');
        }
        return redirect()->route('admin.subjects.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit Subject' : 'Create Subject' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Name" wire:model.live="form.name" />
            <x-input label="Description" wire:model.live="form.description" />
            <x-toggle label="Active" wire:model.live="form.is_active" />

            <x-card-footer back-route="{{ route('admin.subjects.index') }}" back-label="Back" button-label="Save" />
        </x-form>
    </x-card>
</div>
