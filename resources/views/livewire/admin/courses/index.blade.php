<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Manage Courses')] class extends Component {}; ?>

<div>
    <x-header title="Manage Courses" separator />

    <x-card-link title="Courses" link="{{ route('admin.courses.create') }}" icon="o-plus" text="Add Course" permission="create_courses">
        <livewire:admin.course-table />
    </x-card-link>
</div>
