<?php

use Livewire\Volt\Component;

new class extends Component {};
?>

<div>
    <x-slot:sidebar drawer="main-drawer" collapsible class="bg-white">

        {{-- BRAND --}}
        <div class="flex justify-center items-center space-x-3 py-4">
            <img src="/images/L.png" alt="Logo" class="h-16 w-auto" />
        </div>

        <x-menu-separator />

        <x-menu activate-by-route>

            {{-- USER CHECK --}}
            @if ($user = auth()->user())

                {{-- DASHBOARD (COMMON) --}}
                <x-menu-item title="Dashboard" icon="o-home" link="/dashboard" />

                {{-- EMAIL VERIFICATION CHECK --}}
                @if ($user->hasVerifiedEmail())

                    {{-- ================= SUPER ADMIN ================= --}}
                    @role('superadmin')
                        <x-menu-sub title="Administration" icon="o-cog">
                            <x-menu-item title="Users" icon="o-users" link="/superadmin/users" />
                            <x-menu-item title="Roles" icon="o-user-group" link="/superadmin/roles" />
                            <x-menu-item title="Permissions" icon="o-key" link="/superadmin/permissions" />
                        </x-menu-sub>
                    @endrole

                    {{-- ================= ADMIN ================= --}}
                    @if(auth()->user()->hasRole('admin'))
                        <x-menu-sub title="Admin Panel" icon="o-cog">
                            <x-menu-item title="Users" icon="o-users" link="/admin/users" />
                            <x-menu-item title="Subjects" icon="o-bookmark" link="/admin/subjects" />
                            <x-menu-item title="Courses" icon="o-academic-cap" link="/admin/courses" />
                        </x-menu-sub>
                    @endif

                    {{-- ================= TEACHER ================= --}}
    @if(auth()->user()->hasAnyRole(['admin', 'teacher']))
        <x-menu-item title="My Resources" icon="o-document-duplicate" link="/teacher/resources" />
    @endif

                    {{-- ================= STUDENT ================= --}}
                    @if(auth()->user()->hasRole('student'))
                        <x-menu-sub title="Learning Area" icon="o-book-open">
                            <x-menu-item title="Library" icon="o-users" link="/student/library" />
                            <x-menu-item title="Courses" icon="o-users" link="/student/courses" />
                            <x-menu-item title="Bookmarks" icon="o-bookmark" link="/student/bookmarks" />
                        </x-menu-sub>
                    @endif

                    {{-- ================= COMMON PAGES ================= --}}
                    <x-menu-item title="Category" icon="o-tag" link="/category" />
                    <x-menu-item title="Profile" icon="o-user" link="/profile" />

                @else
                    {{-- EMAIL NOT VERIFIED --}}
                    <div class="p-4 mt-2 text-sm bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                        <p>Please verify your email to access all features.</p>
                        <a href="{{ route('verification.notice') }}" class="text-blue-600 hover:underline">
                            Verify Now
                        </a>
                    </div>
                @endif

            @endif

            <x-menu-separator />

            {{-- USER FOOTER --}}
            @if ($user)
                <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                    class="-mx-2 !-my-2 rounded">

                    <x-slot:actions>
                        <div class="flex items-center gap-2">
                            <x-button
                                icon="o-arrow-right-start-on-rectangle"
                                class="btn-circle btn-ghost btn-xs"
                                tooltip-left="Log-out"
                                no-wire-navigate
                                link="/logout"
                            />
                        </div>
                    </x-slot:actions>

                </x-list-item>
            @endif

        </x-menu>
    </x-slot:sidebar>
</div>