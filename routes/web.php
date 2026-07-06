<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;

Volt::route('/', 'landing')->name('landing');

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'auth.login')->name('login');
    Volt::route('/register', 'auth.register')->name('register');
    Volt::route('/forgot-password', 'auth.forgot-password')->name('password.request');
    Volt::route('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
});

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if ($user->hasVerifiedEmail()) {
        return redirect('/');
    }

    $user->markEmailAsVerified();
    $user->previously_verified = true;
    $user->save();

    if (!Auth::check()) {
        Auth::login($user);
    }

    $user->sendEmailVerificationNotification();
    return redirect('/')->with('verified', 'Email verified successfully!');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::middleware('auth')->group(function () {
    Volt::route('/email/verify', 'auth.verify-email')->name('verification.notice');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('/dashboard', 'dashboard')->name('dashboard')->middleware('permission:access_dashboard');
    Volt::route('/profile', 'profile')->name('profile');
    Volt::route('/logout', 'auth.logout')->name('logout');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Volt::route('/users', 'admin.users.index')->name('users.index');
        Volt::route('/users/create', 'admin.users.create-or-edit')->name('users.create');
        Volt::route('/users/{id}/edit', 'admin.users.create-or-edit')->name('users.edit');

        Volt::route('/subjects', 'admin.subjects.index')->name('subjects.index');
        Volt::route('/subjects/create', 'admin.subjects.create-or-edit')->name('subjects.create');
        Volt::route('/subjects/{id}/edit', 'admin.subjects.create-or-edit')->name('subjects.edit');

        Volt::route('/courses', 'admin.courses.index')->name('courses.index');
        Volt::route('/courses/create', 'admin.courses.create-or-edit')->name('courses.create');
        Volt::route('/courses/{id}/edit', 'admin.courses.create-or-edit')->name('courses.edit');
    });

    // Teacher routes (admin can also manage resources)
    Route::middleware('role:admin|teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Volt::route('/resources', 'teacher.resources.index')->name('resources.index');
        Volt::route('/resources/create', 'teacher.resources.create-or-edit')->name('resources.create');
        Volt::route('/resources/{id}/edit', 'teacher.resources.create-or-edit')->name('resources.edit');
    });

    // Student routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Volt::route('/library', 'student.library.index')->name('library.index');
        Volt::route('/courses', 'student.courses.index')->name('courses.index');
        Volt::route('/bookmarks', 'student.bookmarks.index')->name('bookmarks.index');

        Route::get('/resources/{resource}/download', function (\App\Models\Resource $resource) {
            $resource->incrementDownloads();
            $file = $resource->primaryFile;

            if (!$file || !$file->path) {
                abort(404);
            }

            return response()->download(
                storage_path('app/public/' . $file->path),
                $file->original_name
            );
        })->name('resources.download');
    });
});
