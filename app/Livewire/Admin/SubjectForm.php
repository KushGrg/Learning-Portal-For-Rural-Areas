<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SubjectForm extends Form
{
    public ?int $subjectId = null;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('required|boolean')]
    public $is_active = true;

    public function create(): Subject
    {
        $this->validate();

        $subject = Subject::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $this->reset();
        return $subject;
    }

    public function update(): bool
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $this->subjectId,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'required|boolean',
        ]);

        Subject::findOrFail($this->subjectId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'updated_by' => auth()->id(),
        ]);

        return true;
    }

    public function edit(Subject $subject): void
    {
        $this->subjectId = $subject->id;
        $this->name = $subject->name;
        $this->description = $subject->description ?? '';
        $this->is_active = $subject->is_active;
    }
}
