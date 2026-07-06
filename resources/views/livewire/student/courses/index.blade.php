<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Courses')] class extends Component {}; ?>

<div>
    <x-header title="Available Courses" separator />

    <livewire:student.course-table />
</div>
