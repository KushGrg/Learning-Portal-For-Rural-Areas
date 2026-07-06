<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Manage Users')] class extends Component {}; ?>

<div>
    <x-header title="Manage Users" separator />

    <x-card-link title="Users" link="{{ route('admin.users.create') }}" icon="o-plus" text="Add User" permission="create_users">
        <livewire:admin.user-table />
    </x-card-link>
</div>
