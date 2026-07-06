<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class UserTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public string $roleFilter = '';
    public int $perPage = 10;

    public function deleting(User $user): void
    {
        $user->delete();
        $this->success('User deleted successfully.');
    }

    public function togglingActive(User $user): void
    {
        $user->update(['email_verified_at' => $user->email_verified_at ? null : now()]);
        $this->success('User status updated.');
    }

    public function render()
    {
        $query = User::with('roles')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->roleFilter, fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('name', $this->roleFilter)));

        return view('livewire.admin.users.user-table', [
            'users' => $query->latest()->paginate($this->perPage),
        ]);
    }
}
