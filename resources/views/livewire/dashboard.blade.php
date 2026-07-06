<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Models\Course;
use App\Models\Resource;
use App\Models\User;
use App\Models\Enrollment;

new #[Layout('components.layouts.app')] #[Title('Dashboard')] class extends Component {
    public array $stats = [];

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $this->stats = [
                'total_users' => User::count(),
                'total_courses' => Course::count(),
                'total_resources' => Resource::count(),
                'total_enrollments' => Enrollment::count(),
                'recent_users' => User::latest()->take(5)->get(),
                'recent_courses' => Course::with(['subject', 'teacher'])->latest()->take(5)->get(),
            ];
        } elseif ($user->hasRole('teacher')) {
            $this->stats = [
                'my_courses' => Course::where('teacher_id', $user->id)->count(),
                'my_resources' => Resource::where('teacher_id', $user->id)->count(),
                'total_views' => Resource::where('teacher_id', $user->id)->sum('views'),
                'total_downloads' => Resource::where('teacher_id', $user->id)->sum('downloads'),
                'recent_resources' => Resource::where('teacher_id', $user->id)->latest()->take(5)->get(),
            ];
        } else {
            $this->stats = [
                'enrolled_courses' => Enrollment::where('user_id', $user->id)->count(),
                'bookmarks' => $user->bookmarks()->count(),
                'recent_activity' => $user->enrollments()->with('course')->latest()->take(5)->get(),
            ];
        }
    }
}; ?>

<div>
    <x-header title="Dashboard" separator progress-indicator />

    @if(auth()->user()->hasRole('admin'))
        {{-- Admin Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card class="bg-primary text-primary-content">
                <div class="stat-title text-primary-content">Total Users</div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
            </x-card>
            <x-card class="bg-secondary text-secondary-content">
                <div class="stat-title text-secondary-content">Total Courses</div>
                <div class="stat-value">{{ $stats['total_courses'] }}</div>
            </x-card>
            <x-card class="bg-accent text-accent-content">
                <div class="stat-title text-accent-content">Total Resources</div>
                <div class="stat-value">{{ $stats['total_resources'] }}</div>
            </x-card>
            <x-card class="bg-info text-info-content">
                <div class="stat-title text-info-content">Total Enrollments</div>
                <div class="stat-value">{{ $stats['total_enrollments'] }}</div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card title="Recent Users">
                @foreach($stats['recent_users'] as $user)
                    <div class="flex justify-between py-2 border-b">
                        <span>{{ $user->name }}</span>
                        <span class="text-sm text-gray-500">{{ $user->email }}</span>
                    </div>
                @endforeach
            </x-card>
            <x-card title="Recent Courses">
                @foreach($stats['recent_courses'] as $course)
                    <div class="flex justify-between py-2 border-b">
                        <span>{{ $course->title }}</span>
                        <span class="text-sm text-gray-500">{{ $course->subject->name }}</span>
                    </div>
                @endforeach
            </x-card>
        </div>

    @elseif(auth()->user()->hasRole('teacher'))
        {{-- Teacher Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card class="bg-primary text-primary-content">
                <div class="stat-title text-primary-content">My Courses</div>
                <div class="stat-value">{{ $stats['my_courses'] }}</div>
            </x-card>
            <x-card class="bg-secondary text-secondary-content">
                <div class="stat-title text-secondary-content">My Resources</div>
                <div class="stat-value">{{ $stats['my_resources'] }}</div>
            </x-card>
            <x-card class="bg-accent text-accent-content">
                <div class="stat-title text-accent-content">Total Views</div>
                <div class="stat-value">{{ $stats['total_views'] }}</div>
            </x-card>
            <x-card class="bg-info text-info-content">
                <div class="stat-title text-info-content">Total Downloads</div>
                <div class="stat-value">{{ $stats['total_downloads'] }}</div>
            </x-card>
        </div>

        <x-card title="Recent Uploads">
            @forelse($stats['recent_resources'] as $resource)
                <div class="flex justify-between py-2 border-b">
                    <div>
                        <span class="font-medium">{{ $resource->title }}</span>
                        <span class="badge badge-sm ml-2">{{ ucfirst($resource->file_type) }}</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $resource->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-gray-500">No resources uploaded yet.</p>
            @endforelse
        </x-card>

    @else
        {{-- Student Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-card class="bg-primary text-primary-content">
                <div class="stat-title text-primary-content">Enrolled Courses</div>
                <div class="stat-value">{{ $stats['enrolled_courses'] }}</div>
            </x-card>
            <x-card class="bg-secondary text-secondary-content">
                <div class="stat-title text-secondary-content">Bookmarks</div>
                <div class="stat-value">{{ $stats['bookmarks'] }}</div>
            </x-card>
            <x-card class="bg-accent text-accent-content">
                <div class="stat-title text-accent-content">Quick Links</div>
                <div class="flex gap-2 mt-2">
                    <a href="{{ route('student.library.index') }}" class="btn btn-sm btn-ghost">Library</a>
                    <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-ghost">Courses</a>
                    <a href="{{ route('student.bookmarks.index') }}" class="btn btn-sm btn-ghost">Bookmarks</a>
                </div>
            </x-card>
        </div>

        <x-card title="Recent Activity">
            @forelse($stats['recent_activity'] as $enrollment)
                <div class="flex justify-between py-2 border-b">
                    <span>{{ $enrollment->course->title }}</span>
                    <x-badge :value="$enrollment->status" />
                </div>
            @empty
                <p class="text-gray-500">No activity yet. Browse courses to get started!</p>
            @endforelse
        </x-card>
    @endif
</div>
