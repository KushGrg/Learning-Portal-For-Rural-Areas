<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
     #[Validate('required|unique:categories,name', message: [
        'unique' => 'This name already exists.',
    ])]
    public $name;

    #[Validate('required' )]
    public $status;
    public function create()
    {
        Category::create([
            'name' => $this->name,
            'is_active' => $this->status,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $this->reset();

    }
}
