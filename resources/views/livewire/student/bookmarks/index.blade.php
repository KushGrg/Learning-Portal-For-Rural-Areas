<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('My Bookmarks')] class extends Component {}; ?>

<div>
    <x-header title="My Bookmarks" separator />

    <livewire:student.bookmark-table />
</div>
