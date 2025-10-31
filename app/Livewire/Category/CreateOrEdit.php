<?php

namespace App\Livewire\Category;

use App\Enums\Status;
use App\Livewire\Category\CategoryForm;
use Livewire\Component;
use App\Traits\HasStatus;
use Mary\Traits\Toast;

class CreateOrEdit extends Component
{
    use HasStatus, Toast;

    public CategoryForm $form;
    public array $statusOptions = [];
    public function mount()
    {
        $this->statusOptions = $this->status(Status::class, 'value', 'name');

    }

    public function create()
    {
        $this->form->validate();
        try {
            $this->form->create();
            $this->success('Category created sucessfully.');
        } catch (\Exception $e) {
            $this->error('Failed to create category.');
                \Log::info('Failed to create category'. $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.category.create-or-edit');
    }
}
