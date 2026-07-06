<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Resource Library')] class extends Component {}; ?>

<div>
    <x-header title="Resource Library" separator />

    <livewire:student.library-table />
</div>
