<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Role;

class UserForm extends Form
{
    public ?int $userId = null;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|min:6')]
    public $password = '';

    #[Validate('required')]
    public $role = '';

    public array $roleOptions = [];

    public function mount(): void
    {
        $this->roleOptions = Role::all()->pluck('name', 'name')->toArray();
    }

    public function rules(): array
    {
        $emailRule = $this->userId
            ? 'required|email|max:255|unique:users,email,' . $this->userId
            : 'required|email|max:255|unique:users,email';

        return [
            'email' => $emailRule,
        ];
    }

    public function create(): User
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($this->role);

        $this->reset();
        return $user;
    }

    public function update(): bool
    {
        $this->validate();

        $user = User::findOrFail($this->userId);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        $user->update($data);
        $user->syncRoles([$this->role]);

        return true;
    }

    public function edit(User $user): void
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->getRoleNames()->first() ?? '';
    }
}
