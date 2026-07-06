<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Manage Subjects')] class extends Component {}; ?>

<div>
    <x-header title="Manage Subjects" separator />

    <x-card-link title="Subjects" link="{{ route('admin.subjects.create') }}" icon="o-plus" text="Add Subject" permission="create_subjects">
        <livewire:admin.subject-table />
    </x-card-link>
</div>
