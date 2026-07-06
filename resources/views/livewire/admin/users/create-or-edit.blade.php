<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Admin\UserForm;

new #[Layout('components.layouts.app')] #[Title('User')] class extends Component {
    public UserForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $user = \App\Models\User::findOrFail($id);
            $this->form->edit($user);
            $this->isEdit = true;
        }
    }

    public function save(): mixed
    {
        if ($this->isEdit) {
            $this->form->update();
            $this->success('User updated successfully.');
        } else {
            $this->form->create();
            $this->success('User created successfully.');
        }
        return redirect()->route('admin.users.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit User' : 'Create User' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Name" wire:model.live="form.name" />
            <x-input label="Email" wire:model.live="form.email" type="email" />
            <x-input label="Password" wire:model.live="form.password" type="password"
                hint="{{ $isEdit ? 'Leave blank to keep current password' : '' }}" />
            <x-select label="Role" wire:model.live="form.role" :options="$form->roleOptions"
                option-value="value" option-label="label" placeholder="Select role" />

            <x-card-footer back-route="{{ route('admin.users.index') }}" back-label="Back" button-label="Save" spinner="saving" />
        </x-form>
    </x-card>
</div>
