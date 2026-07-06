<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('My Resources')] class extends Component {}; ?>

<div>
    <x-header title="My Resources" separator />

    <x-card-link title="Resources" link="{{ route('teacher.resources.create') }}" icon="o-plus" text="Upload Resource" permission="upload_resources">
        <livewire:teacher.resource-table />
    </x-card-link>
</div>
