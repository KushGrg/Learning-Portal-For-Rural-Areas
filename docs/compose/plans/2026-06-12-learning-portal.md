# Learning Portal Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use compose:subagent (recommended) or compose:execute to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a complete learning platform for rural areas with role-based access (Admin, Teacher, Student), course/resource management, file uploads, and dashboards.

**Architecture:** Laravel 12 + Livewire 3 + Volt + Mary UI. Spatie Permission for RBAC. MySQL database. Local file storage with public disk for resources. Each feature is a Livewire Volt component with dedicated Livewire classes for complex logic.

**Tech Stack:** Laravel 12, Livewire 3, Volt, Mary UI, Spatie Permission, DaisyUI, Tailwind CSS, Pest for tests

---

## File Structure

### New Models
- `app/Models/Subject.php`
- `app/Models/Course.php`
- `app/Models/Topic.php`
- `app/Models/Resource.php`
- `app/Models/ResourceFile.php`
- `app/Models/Enrollment.php`
- `app/Models/Bookmark.php`

### Modified Models
- `app\Models/User.php` — add relationships

### New Migrations
- `database/migrations/2026_06_12_000001_create_subjects_table.php`
- `database/migrations/2026_06_12_000002_create_courses_table.php`
- `database/migrations/2026_06_12_000003_create_topics_table.php`
- `database/migrations/2026_06_12_000004_create_resources_table.php`
- `database/migrations/2026_06_12_000005_create_resource_files_table.php`
- `database/migrations/2026_06_12_000006_create_enrollments_table.php`
- `database/migrations/2026_06_12_000007_create_bookmarks_table.php`

### Seeders
- `database/seeders/RolesAndPermissionsSeeder.php` — update with learning portal permissions
- `database/seeders/SubjectSeeder.php`
- `database/seeders/CourseSeeder.php`

### Livewire Components (Volt)
- `resources/views/livewire/admin/users/index.blade.php`
- `resources/views/livewire/admin/users/create-or-edit.blade.php`
- `resources/views/livewire/admin/subjects/index.blade.php`
- `resources/views/livewire/admin/subjects/create-or-edit.blade.php`
- `resources/views/livewire/admin/courses/index.blade.php`
- `resources/views/livewire/admin/courses/create-or-edit.blade.php`
- `resources/views/livewire/teacher/resources/index.blade.php`
- `resources/views/livewire/teacher/resources/create-or-edit.blade.php`
- `resources/views/livewire/student/library/index.blade.php`
- `resources/views/livewire/student/courses/index.blade.php`
- `resources/views/livewire/student/bookmarks/index.blade.php`
- `resources/views/livewire/dashboard.blade.php` — update

### Livewire Classes
- `app/Livewire/Admin/UserForm.php`
- `app/Livewire/Admin/UserTable.php`
- `app/Livewire/Admin/SubjectForm.php`
- `app/Livewire/Admin/SubjectTable.php`
- `app/Livewire/Admin/CourseForm.php`
- `app/Livewire/Admin/CourseTable.php`
- `app/Livewire/Teacher/ResourceForm.php`
- `app/Livewire/Teacher/ResourceTable.php`
- `app/Livewire/Student/LibraryTable.php`
- `app/Livewire/Student/CourseTable.php`
- `app/Livewire/Student/BookmarkTable.php`

### Enums
- `app/Enums/FileType.php`

### Routes
- `routes/web.php` — add all routes

---

## Task 1: Database Migrations

**Covers:** §4 Database Structure

**Files:**
- Create: `database/migrations/2026_06_12_000001_create_subjects_table.php`
- Create: `database/migrations/2026_06_12_000002_create_courses_table.php`
- Create: `database/migrations/2026_06_12_000003_create_topics_table.php`
- Create: `database/migrations/2026_06_12_000004_create_resources_table.php`
- Create: `database/migrations/2026_06_12_000005_create_resource_files_table.php`
- Create: `database/migrations/2026_06_12_000006_create_enrollments_table.php`
- Create: `database/migrations/2026_06_12_000007_create_bookmarks_table.php`

- [ ] **Step 1: Create subjects migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
```

- [ ] **Step 2: Create courses migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users');
            $table->string('grade_level')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
```

- [ ] **Step 3: Create topics migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
```

- [ ] **Step 4: Create resources migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('tags')->nullable();
            $table->enum('file_type', ['pdf', 'video', 'image']);
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->constrained('users');
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
```

- [ ] **Step 5: Create resource_files migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('youtube_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_files');
    }
};
```

- [ ] **Step 6: Create enrollments migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'completed', 'dropped'])->default('enrolled');
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
```

- [ ] **Step 7: Create bookmarks migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'resource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
```

- [ ] **Step 8: Run migrations**

```bash
php artisan migrate
```

Expected: All tables created successfully.

---

## Task 2: Models & Relationships

**Covers:** §2 User Roles, §3.4 Resource Management, §3.5 Resource Library

**Files:**
- Create: `app/Models/Subject.php`
- Create: `app/Models/Course.php`
- Create: `app/Models/Topic.php`
- Create: `app/Models/Resource.php`
- Create: `app/Models/ResourceFile.php`
- Create: `app/Models/Enrollment.php`
- Create: `app/Models/Bookmark.php`
- Modify: `app/Models/User.php`

- [ ] **Step 1: Create Subject model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Subject $subject) {
            $subject->slug = Str::slug($subject->name);
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
```

- [ ] **Step 2: Create Course model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'subject_id',
        'teacher_id',
        'grade_level',
        'is_published',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            $course->slug = Str::slug($course->title);
        });
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class)->orderBy('order');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledUsers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Enrollment::class);
    }
}
```

- [ ] **Step 3: Create Topic model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
```

- [ ] **Step 4: Create Resource model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'tags',
        'file_type',
        'course_id',
        'topic_id',
        'teacher_id',
        'views',
        'downloads',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'views' => 'integer',
        'downloads' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Resource $resource) {
            $resource->slug = Str::slug($resource->title);
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ResourceFile::class);
    }

    public function primaryFile(): HasOne
    {
        return $this->hasOne(ResourceFile::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function isBookmarkedBy(User $user): bool
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function incrementDownloads(): void
    {
        $this->increment('downloads');
    }
}
```

- [ ] **Step 5: Create ResourceFile model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'youtube_url',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->youtube_url) {
            return $this->youtube_url;
        }
        return asset('storage/' . $this->path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
```

- [ ] **Step 6: Create Enrollment model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
```

- [ ] **Step 7: Create Bookmark model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
```

- [ ] **Step 8: Update User model with relationships**

Add to `app\Models\User.php`:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

// Add these relationships after the existing casts property:

public function taughtCourses(): HasMany
{
    return $this->hasMany(Course::class, 'teacher_id');
}

public function enrolledCourses(): HasManyThrough
{
    return $this->hasManyThrough(Course::class, Enrollment::class);
}

public function enrollments(): HasMany
{
    return $this->hasMany(Enrollment::class);
}

public function bookmarks(): HasMany
{
    return $this->hasMany(Bookmark::class);
}

public function resources(): HasMany
{
    return $this->hasMany(Resource::class, 'teacher_id');
}

public function createdResources(): HasMany
{
    return $this->hasMany(Resource::class, 'created_by');
}
```

- [ ] **Step 9: Verify models load**

```bash
php artisan tinker --execute="echo 'Models OK';"
```

Expected: No errors.

---

## Task 3: Roles, Permissions & Seeders

**Covers:** §2 User Roles, §3.1 Authentication & Roles

**Files:**
- Modify: `database/seeders/RolesAndPermissionsSeeder.php`
- Create: `database/seeders/SubjectSeeder.php`
- Create: `database/seeders/CourseSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Update RolesAndPermissionsSeeder**

Replace `database/seeders/RolesAndPermissionsSeeder.php` with:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access_dashboard',

            // User management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Role management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            // Subject management
            'view_subjects',
            'create_subjects',
            'edit_subjects',
            'delete_subjects',

            // Course management
            'view_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            'publish_courses',

            // Resource management
            'view_resources',
            'create_resources',
            'edit_resources',
            'delete_resources',
            'upload_resources',

            // Student actions
            'enroll_courses',
            'bookmark_resources',
            'download_resources',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'access_dashboard',
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'view_subjects', 'create_subjects', 'edit_subjects', 'delete_subjects',
            'view_courses', 'create_courses', 'edit_courses', 'delete_courses', 'publish_courses',
            'view_resources', 'create_resources', 'edit_resources', 'delete_resources', 'upload_resources',
        ]);

        // Teacher role
        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'access_dashboard',
            'view_courses', 'create_courses', 'edit_courses',
            'view_resources', 'create_resources', 'edit_resources', 'delete_resources', 'upload_resources',
            'view_subjects',
        ]);

        // Student role
        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'access_dashboard',
            'view_courses', 'view_resources',
            'enroll_courses', 'bookmark_resources', 'download_resources',
        ]);
    }
}
```

- [ ] **Step 2: Create SubjectSeeder**

```php
<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) return;

        $subjects = [
            ['name' => 'Mathematics', 'description' => 'Mathematical concepts and problem solving'],
            ['name' => 'Science', 'description' => 'Physics, Chemistry, and Biology'],
            ['name' => 'English', 'description' => 'English language and literature'],
            ['name' => 'Nepali', 'description' => 'Nepali language and literature'],
            ['name' => 'Social Studies', 'description' => 'History, Geography, and Civics'],
            ['name' => 'Computer Science', 'description' => 'Computer fundamentals and programming'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                ...$subject,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }
    }
}
```

- [ ] **Step 3: Create CourseSeeder**

```php
<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) return;

        $subjects = Subject::all();
        if ($subjects->isEmpty()) return;

        $courses = [
            ['title' => 'Basic Mathematics', 'subject' => 'Mathematics', 'grade_level' => 'Grade 6', 'description' => 'Introduction to basic mathematical concepts'],
            ['title' => 'Introduction to Physics', 'subject' => 'Science', 'grade_level' => 'Grade 8', 'description' => 'Basic physics concepts for beginners'],
            ['title' => 'English Grammar Fundamentals', 'subject' => 'English', 'grade_level' => 'Grade 7', 'description' => 'Essential English grammar rules'],
        ];

        foreach ($courses as $courseData) {
            $subject = $subjects->firstWhere('name', $courseData['subject']);
            if (!$subject) continue;

            Course::create([
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'grade_level' => $courseData['grade_level'],
                'is_published' => true,
                'created_by' => $teacher->id,
                'updated_by' => $teacher->id,
            ]);
        }
    }
}
```

- [ ] **Step 4: Update DatabaseSeeder**

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create teacher user
        $teacher = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('teacher');

        // Create student user
        $student = User::create([
            'name' => 'Student',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $student->assignRole('student');

        $this->call([
            SubjectSeeder::class,
            CourseSeeder::class,
        ]);
    }
}
```

- [ ] **Step 5: Run seeders**

```bash
php artisan migrate:fresh --seed
```

Expected: Database seeded with roles, permissions, users, subjects, and courses.

---

## Task 4: Enums & File Upload Helper

**Covers:** §3.8 File Management

**Files:**
- Create: `app/Enums/FileType.php`
- Create: `app/Services/FileUploadService.php`

- [ ] **Step 1: Create FileType enum**

```php
<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FileType extends Enum
{
    const Pdf = 'pdf';
    const Video = 'video';
    const Image = 'image';

    public static function labels(): array
    {
        return [
            self::Pdf => 'PDF Document',
            self::Video => 'Video',
            self::Image => 'Image',
        ];
    }
}
```

- [ ] **Step 2: Create FileUploadService**

```php
<?php

namespace App\Services;

use App\Models\ResourceFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function uploadResourceFile(UploadedFile $file, int $resourceId): ResourceFile
    {
        $originalName = $file->getClientOriginalName();
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('resources/' . $resourceId, $storedName, 'public');

        return ResourceFile::create([
            'resource_id' => $resourceId,
            'original_name' => $originalName,
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function deleteResourceFile(ResourceFile $file): bool
    {
        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        return $file->delete();
    }

    public function getYoutubeEmbedUrl(string $url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }

        return null;
    }
}
```

- [ ] **Step 3: Register service in AppServiceProvider**

Add to `app/Providers/AppServiceProvider.php` `register()` method:

```php
use App\Services\FileUploadService;

$this->app->singleton(FileUploadService::class, function () {
    return new FileUploadService();
});
```

---

## Task 5: Admin — User Management

**Covers:** §2 User Roles (Admin), §3.2 User Management

**Files:**
- Create: `app/Livewire/Admin/UserForm.php`
- Create: `app/Livewire/Admin/UserTable.php`
- Create: `resources/views/livewire/admin/users/index.blade.php`
- Create: `resources/views/livewire/admin/users/create-or-edit.blade.php`

- [ ] **Step 1: Create UserForm Livewire Form**

```php
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
```

- [ ] **Step 2: Create UserTable Livewire Component**

```php
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
```

- [ ] **Step 3: Create user-table blade view**

```php
<div>
    <div class="flex flex-wrap gap-4 mb-4">
        <x-input placeholder="Search users..." wire:model.live="search" icon="o-magnifying-glass" />
        <x-select placeholder="Filter by role" wire:model.live="roleFilter"
            :options="[['value' => '', 'label' => 'All'], ['value' => 'admin', 'label' => 'Admin'], ['value' => 'teacher', 'label' => 'Teacher'], ['value' => 'student', 'label' => 'Student']]"
            option-value="value" option-label="label" />
    </div>

    <x-table :headers="['#', 'Name', 'Email', 'Role', 'Status', 'Actions']" :rows="$users">
        @scope('cell_id', $user)
            {{ $user->id }}
        @endscope
        @scope('cell_name', $user)
            {{ $user->name }}
        @endscope
        @scope('cell_email', $user)
            {{ $user->email }}
        @endscope
        @scope('cell_role', $user)
            <x-badge :value="$user->getRoleNames()->first() ?? 'N/A'" class="badge-primary" />
        @endscope
        @scope('cell_status', $user)
            <x-badge :value="$user->hasVerifiedEmail() ? 'Active' : 'Inactive'"
                :class="$user->hasVerifiedEmail() ? 'badge-success' : 'badge-warning'" />
        @endscope
        @scope('cell_actions', $user)
            <div class="flex gap-1">
                <x-button icon="o-pencil" link="/admin/users/{{ $user->id }}/edit" class="btn-ghost btn-sm" />
                <x-button icon="o-trash" wire:click="deleting({{ $user->id }})" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-error" />
            </div>
        @endscope
    </x-table>

    {{ $users->links() }}
</div>
```

- [ ] **Step 4: Create users index blade**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Manage Users')] class extends Component {}; ?>

<div>
    <x-header title="Manage Users" separator />

    <x-card-link title="Users" link="{{ route('admin.users.create') }}" icon="o-plus" text="Add User" permission="create_users">
        <livewire:admin.user-table />
    </x-card-link>
</div>
```

- [ ] **Step 5: Create users create-or-edit blade**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Admin\UserForm;

new #[Layout('components.layouts.app')] #[Title('User')] class extends Component {
    public UserForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $user = \App\Models\User::findOrFail($id);
            $this->form->edit($user);
            $this->isEdit = true;
        }
    }

    public function save(): void
    {
        if ($this->isEdit) {
            $this->form->update();
            $this->success('User updated successfully.');
        } else {
            $this->form->create();
            $this->success('User created successfully.');
        }
        return redirect()->route('admin.users.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit User' : 'Create User' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Name" wire:model.live="form.name" />
            <x-input label="Email" wire:model.live="form.email" type="email" />
            <x-input label="Password" wire:model.live="form.password" type="password"
                hint="{{ $isEdit ? 'Leave blank to keep current password' : '' }}" />
            <x-select label="Role" wire:model.live="form.role" :options="$form->roleOptions"
                option-value="value" option-label="label" placeholder="Select role" />

            <x-card-footer back-route="{{ route('admin.users.index') }}" back-label="Back" button-label="Save" spinner="saving" />
        </x-form>
    </x-card>
</div>
```

---

## Task 6: Admin — Subject Management

**Covers:** §3.3 Course Management (subjects are prerequisites)

**Files:**
- Create: `app/Livewire/Admin/SubjectForm.php`
- Create: `app/Livewire/Admin/SubjectTable.php`
- Create: `resources/views/livewire/admin/subjects/index.blade.php`
- Create: `resources/views/livewire/admin/subjects/create-or-edit.blade.php`

- [ ] **Step 1: Create SubjectForm**

```php
<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SubjectForm extends Form
{
    public ?int $subjectId = null;

    #[Validate('required|string|max:255|unique:subjects,name')]
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
        $rules = $this->rules();
        $rules['name'] = 'required|string|max:255|unique:subjects,name,' . $this->subjectId;
        $this->validate($rules);

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
```

- [ ] **Step 2: Create SubjectTable**

```php
<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class SubjectTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    public function deleting(Subject $subject): void
    {
        $subject->delete();
        $this->success('Subject deleted.');
    }

    public function render()
    {
        return view('livewire.admin.subjects.subject-table', [
            'subjects' => Subject::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
        ]);
    }
}
```

- [ ] **Step 3: Create subject-table blade**

```php
<div>
    <div class="mb-4">
        <x-input placeholder="Search subjects..." wire:model.live="search" icon="o-magnifying-glass" />
    </div>

    <x-table :headers="['#', 'Name', 'Description', 'Status', 'Actions']">
        @foreach($subjects as $subject)
            <x-table.row>
                <x-table.cell>{{ $subject->id }}</x-table.cell>
                <x-table.cell>{{ $subject->name }}</x-table.cell>
                <x-table.cell>{{ Str::limit($subject->description, 50) }}</x-table.cell>
                <x-table.cell>
                    <x-badge :value="$subject->is_active ? 'Active' : 'Inactive'"
                        :class="$subject->is_active ? 'badge-success' : 'badge-warning'" />
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-1">
                        <x-button icon="o-pencil" link="{{ route('admin.subjects.edit', $subject) }}" class="btn-ghost btn-sm" />
                        <x-button icon="o-trash" wire:click="deleting({{ $subject->id }})" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-error" />
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforeach
    </x-table>

    {{ $subjects->links() }}
</div>
```

- [ ] **Step 4: Create subjects index blade**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Manage Subjects')] class extends Component {}; ?>

<div>
    <x-header title="Manage Subjects" separator />

    <x-card-link title="Subjects" link="{{ route('admin.subjects.create') }}" icon="o-plus" text="Add Subject" permission="create_subjects">
        <livewire:admin.subject-table />
    </x-card-link>
</div>
```

- [ ] **Step 5: Create subjects create-or-edit blade**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Admin\SubjectForm;

new #[Layout('components.layouts.app')] #[Title('Subject')] class extends Component {
    public SubjectForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $this->form->edit(\App\Models\Subject::findOrFail($id));
            $this->isEdit = true;
        }
    }

    public function save(): void
    {
        if ($this->isEdit) {
            $this->form->update();
            $this->success('Subject updated.');
        } else {
            $this->form->create();
            $this->success('Subject created.');
        }
        return redirect()->route('admin.subjects.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit Subject' : 'Create Subject' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Name" wire:model.live="form.name" />
            <x-input label="Description" wire:model.live="form.description" />
            <x-toggle label="Active" wire:model.live="form.is_active" />

            <x-card-footer back-route="{{ route('admin.subjects.index') }}" back-label="Back" button-label="Save" />
        </x-form>
    </x-card>
</div>
```

---

## Task 7: Admin — Course Management

**Covers:** §3.3 Course Management

**Files:**
- Create: `app/Livewire/Admin/CourseForm.php`
- Create: `app/Livewire/Admin/CourseTable.php`
- Create: `resources/views/livewire/admin/courses/index.blade.php`
- Create: `resources/views/livewire/admin/courses/create-or-edit.blade.php`

- [ ] **Step 1: Create CourseForm**

```php
<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CourseForm extends Form
{
    public ?int $courseId = null;

    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('required|integer')]
    public $subject_id = 0;

    #[Validate('required|integer')]
    public $teacher_id = 0;

    #[Validate('nullable|string|max:50')]
    public $grade_level = '';

    #[Validate('required|boolean')]
    public $is_published = false;

    public array $subjectOptions = [];
    public array $teacherOptions = [];

    public function mount(): void
    {
        $this->subjectOptions = Subject::where('is_active', true)->pluck('name', 'id')->toArray();
        $this->teacherOptions = User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->pluck('name', 'id')->toArray();
    }

    public function create(): Course
    {
        $this->validate();

        $course = Course::create([
            'title' => $this->title,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'grade_level' => $this->grade_level,
            'is_published' => $this->is_published,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $this->reset();
        return $course;
    }

    public function update(): bool
    {
        $this->validate();

        Course::findOrFail($this->courseId)->update([
            'title' => $this->title,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'grade_level' => $this->grade_level,
            'is_published' => $this->is_published,
            'updated_by' => auth()->id(),
        ]);

        return true;
    }

    public function edit(Course $course): void
    {
        $this->courseId = $course->id;
        $this->title = $course->title;
        $this->description = $course->description ?? '';
        $this->subject_id = $course->subject_id;
        $this->teacher_id = $course->teacher_id;
        $this->grade_level = $course->grade_level ?? '';
        $this->is_published = $course->is_published;
    }
}
```

- [ ] **Step 2: Create CourseTable**

```php
<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class CourseTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public ?int $subjectFilter = null;

    public function deleting(Course $course): void
    {
        $course->delete();
        $this->success('Course deleted.');
    }

    public function togglePublish(Course $course): void
    {
        $course->update(['is_published' => !$course->is_published]);
        $this->success($course->is_published ? 'Course published.' : 'Course unpublished.');
    }

    public function render()
    {
        return view('livewire.admin.courses.course-table', [
            'courses' => Course::with(['subject', 'teacher'])
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->when($this->subjectFilter, fn ($q) => $q->where('subject_id', $this->subjectFilter))
                ->latest()
                ->paginate(10),
            'subjects' => \App\Models\Subject::where('is_active', true)->get(),
        ]);
    }
}
```

- [ ] **Step 3: Create course-table blade**

```php
<div>
    <div class="flex flex-wrap gap-4 mb-4">
        <x-input placeholder="Search courses..." wire:model.live="search" icon="o-magnifying-glass" />
        <x-select placeholder="Filter by subject" wire:model.live="subjectFilter"
            :options="$subjects->pluck('name', 'id')->toArray()" />
    </div>

    <x-table :headers="['#', 'Title', 'Subject', 'Teacher', 'Grade', 'Published', 'Actions']">
        @foreach($courses as $course)
            <x-table.row>
                <x-table.cell>{{ $course->id }}</x-table.cell>
                <x-table.cell>{{ $course->title }}</x-table.cell>
                <x-table.cell>{{ $course->subject->name }}</x-table.cell>
                <x-table.cell>{{ $course->teacher->name }}</x-table.cell>
                <x-table.cell>{{ $course->grade_level ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <x-badge :value="$course->is_published ? 'Yes' : 'No'"
                        :class="$course->is_published ? 'badge-success' : 'badge-warning'" />
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-1">
                        <x-button icon="o-pencil" link="{{ route('admin.courses.edit', $course) }}" class="btn-ghost btn-sm" />
                        <x-button icon="o-trash" wire:click="deleting({{ $course->id }})" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-error" />
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforeach
    </x-table>

    {{ $courses->links() }}
</div>
```

- [ ] **Step 4: Create courses index blade**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Manage Courses')] class extends Component {}; ?>

<div>
    <x-header title="Manage Courses" separator />

    <x-card-link title="Courses" link="{{ route('admin.courses.create') }}" icon="o-plus" text="Add Course" permission="create_courses">
        <livewire:admin.course-table />
    </x-card-link>
</div>
```

- [ ] **Step 5: Create courses create-or-edit blade**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Admin\CourseForm;

new #[Layout('components.layouts.app')] #[Title('Course')] class extends Component {
    public CourseForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $this->form->edit(\App\Models\Course::findOrFail($id));
            $this->isEdit = true;
        }
    }

    public function save(): void
    {
        if ($this->isEdit) {
            $this->form->update();
            $this->success('Course updated.');
        } else {
            $this->form->create();
            $this->success('Course created.');
        }
        return redirect()->route('admin.courses.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit Course' : 'Create Course' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Title" wire:model.live="form.title" />
            <x-input label="Description" wire:model.live="form.description" />
            <x-select label="Subject" wire:model.live="form.subject_id" :options="$form->subjectOptions" />
            <x-select label="Teacher" wire:model.live="form.teacher_id" :options="$form->teacherOptions" />
            <x-input label="Grade Level" wire:model.live="form.grade_level" placeholder="e.g. Grade 8" />
            <x-toggle label="Published" wire:model.live="form.is_published" />

            <x-card-footer back-route="{{ route('admin.courses.index') }}" back-label="Back" button-label="Save" />
        </x-form>
    </x-card>
</div>
```

---

## Task 8: Teacher — Resource Management

**Covers:** §3.4 Resource Management, §3.8 File Management

**Files:**
- Create: `app/Livewire/Teacher/ResourceForm.php`
- Create: `app/Livewire/Teacher/ResourceTable.php`
- Create: `resources/views/livewire/teacher/resources/index.blade.php`
- Create: `resources/views/livewire/teacher/resources/create-or-edit.blade.php`

- [ ] **Step 1: Create ResourceForm**

```php
<?php

namespace App\Livewire\Teacher;

use App\Enums\FileType;
use App\Models\Course;
use App\Models\Resource;
use App\Models\Topic;
use App\Services\FileUploadService;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class ResourceForm extends Component
{
    use WithFileUploads, Toast;

    public ?int $resourceId = null;

    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('nullable|string|max:500')]
    public $tags = '';

    #[Validate('required|in:pdf,video,image')]
    public $file_type = 'pdf';

    #[Validate('required|integer')]
    public $course_id = 0;

    #[Validate('nullable|integer')]
    public $topic_id = 0;

    #[Validate('nullable|file|max:51200')]
    public $file;

    #[Validate('nullable|url')]
    public $youtube_url = '';

    public array $courseOptions = [];
    public array $topicOptions = [];
    public array $fileTypeOptions = [];

    protected FileUploadService $fileService;

    public function boot(FileUploadService $fileService): void
    {
        $this->fileService = $fileService;
    }

    public function mount(): void
    {
        $this->fileTypeOptions = collect(FileType::cases())
            ->map(fn ($ft) => ['value' => $ft->value, 'label' => FileType::labels()[$ft->value]])
            ->toArray();

        $this->loadCourses();
    }

    public function loadCourses(): void
    {
        $this->courseOptions = Course::where('is_published', true)
            ->when(!auth()->user()->hasRole('admin'), fn ($q) => $q->where('teacher_id', auth()->id()))
            ->pluck('title', 'id')
            ->toArray();
    }

    public function updatedCourseId(): void
    {
        $this->topicOptions = Topic::where('course_id', $this->course_id)
            ->pluck('title', 'id')
            ->toArray();
        $this->topic_id = 0;
    }

    public function create(): Resource
    {
        $this->validate();

        $resource = Resource::create([
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'file_type' => $this->file_type,
            'course_id' => $this->course_id,
            'topic_id' => $this->topic_id ?: null,
            'teacher_id' => auth()->id(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        if ($this->file_type === 'video' && $this->youtube_url) {
            $resource->files()->create([
                'original_name' => 'YouTube Video',
                'stored_name' => 'youtube',
                'path' => '',
                'mime_type' => 'video/youtube',
                'size' => 0,
                'youtube_url' => $this->youtube_url,
            ]);
        } elseif ($this->file) {
            $this->fileService->uploadResourceFile($this->file, $resource->id);
        }

        $this->success('Resource created successfully.');
        $this->reset();
        return $resource;
    }

    public function update(): bool
    {
        $this->validate();

        $resource = Resource::findOrFail($this->resourceId);
        $resource->update([
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'file_type' => $this->file_type,
            'course_id' => $this->course_id,
            'topic_id' => $this->topic_id ?: null,
            'updated_by' => auth()->id(),
        ]);

        if ($this->file) {
            $existingFile = $resource->primaryFile;
            if ($existingFile) {
                $this->fileService->deleteResourceFile($existingFile);
            }
            $this->fileService->uploadResourceFile($this->file, $resource->id);
        }

        $this->success('Resource updated successfully.');
        return true;
    }

    public function edit(Resource $resource): void
    {
        $this->resourceId = $resource->id;
        $this->title = $resource->title;
        $this->description = $resource->description ?? '';
        $this->tags = $resource->tags ?? '';
        $this->file_type = $resource->file_type;
        $this->course_id = $resource->course_id;
        $this->topic_id = $resource->topic_id ?? 0;
        $this->youtube_url = $resource->primaryFile?->youtube_url ?? '';

        $this->updatedCourseId();
    }

    public function render()
    {
        return view('livewire.teacher.resources.resource-form');
    }
}
```

- [ ] **Step 2: Create ResourceTable**

```php
<?php

namespace App\Livewire\Teacher;

use App\Models\Resource;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ResourceTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public string $typeFilter = '';

    protected FileUploadService $fileService;

    public function boot(FileUploadService $fileService): void
    {
        $this->fileService = $fileService;
    }

    public function deleting(Resource $resource): void
    {
        $file = $resource->primaryFile;
        if ($file) {
            $this->fileService->deleteResourceFile($file);
        }
        $resource->delete();
        $this->success('Resource deleted.');
    }

    public function render()
    {
        return view('livewire.teacher.resources.resource-table', [
            'resources' => Resource::with(['course', 'topic', 'files'])
                ->where('teacher_id', auth()->id())
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->when($this->typeFilter, fn ($q) => $q->where('file_type', $this->typeFilter))
                ->latest()
                ->paginate(10),
        ]);
    }
}
```

- [ ] **Step 3: Create resource-table blade**

```php
<div>
    <div class="flex flex-wrap gap-4 mb-4">
        <x-input placeholder="Search resources..." wire:model.live="search" icon="o-magnifying-glass" />
        <x-select placeholder="Filter by type" wire:model.live="typeFilter"
            :options="[['value' => '', 'label' => 'All'], ['value' => 'pdf', 'label' => 'PDF'], ['value' => 'video', 'label' => 'Video'], ['value' => 'image', 'label' => 'Image']]"
            option-value="value" option-label="label" />
    </div>

    <x-table :headers="['#', 'Title', 'Course', 'Type', 'Views', 'Downloads', 'Actions']">
        @foreach($resources as $resource)
            <x-table.row>
                <x-table.cell>{{ $resource->id }}</x-table.cell>
                <x-table.cell>{{ $resource->title }}</x-table.cell>
                <x-table.cell>{{ $resource->course->title }}</x-table.cell>
                <x-table.cell>
                    <x-badge :value ucfirst($resource->file_type) />
                </x-table.cell>
                <x-table.cell>{{ $resource->views }}</x-table.cell>
                <x-table.cell>{{ $resource->downloads }}</x-table.cell>
                <x-table.cell>
                    <div class="flex gap-1">
                        <x-button icon="o-pencil" link="{{ route('teacher.resources.edit', $resource) }}" class="btn-ghost btn-sm" />
                        <x-button icon="o-trash" wire:click="deleting({{ $resource->id }})" wire:confirm="Are you sure?" class="btn-ghost btn-sm text-error" />
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforeach
    </x-table>

    {{ $resources->links() }}
</div>
```

- [ ] **Step 4: Create teacher resources index**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('My Resources')] class extends Component {}; ?>

<div>
    <x-header title="My Resources" separator />

    <x-card-link title="Resources" link="{{ route('teacher.resources.create') }}" icon="o-plus" text="Upload Resource" permission="upload_resources">
        <livewire:teacher.resource-table />
    </x-card-link>
</div>
```

- [ ] **Step 5: Create teacher resources create-or-edit**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Livewire\Teacher\ResourceForm;

new #[Layout('components.layouts.app')] #[Title('Resource')] class extends Component {
    public ResourceForm $form;
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->form->mount();
        if ($id) {
            $this->form->edit(\App\Models\Resource::findOrFail($id));
            $this->isEdit = true;
        }
    }

    public function save(): void
    {
        if ($this->isEdit) {
            $this->form->update();
        } else {
            $this->form->create();
        }
        return redirect()->route('teacher.resources.index');
    }
}; ?>

<div>
    <x-header title="{{ $isEdit ? 'Edit Resource' : 'Upload Resource' }}" separator />

    <x-card>
        <x-form wire:submit.prevent="save">
            <x-input label="Title" wire:model.live="form.title" />
            <x-input label="Description" wire:model.live="form.description" />
            <x-input label="Tags" wire:model.live="form.tags" placeholder="comma-separated" />
            <x-select label="File Type" wire:model.live="form.file_type" :options="$form->fileTypeOptions"
                option-value="value" option-label="label" />
            <x-select label="Course" wire:model.live="form.course_id" :options="$form->courseOptions" />
            <x-select label="Topic" wire:model.live="form.topic_id" :options="$form->topicOptions" />

            @if($form->file_type === 'video')
                <x-input label="YouTube URL" wire:model.live="form.youtube_url" placeholder="https://youtube.com/watch?v=..." />
            @else
                <x-file wire:model.live="form.file" accept=".pdf,.jpg,.jpeg,.png" label="Upload File" />
            @endif

            <x-card-footer back-route="{{ route('teacher.resources.index') }}" back-label="Back" button-label="Save" />
        </x-form>
    </x-card>
</div>
```

---

## Task 9: Student — Resource Library

**Covers:** §3.5 Resource Library, §3.6 Search & Filter

**Files:**
- Create: `app/Livewire/Student/LibraryTable.php`
- Create: `resources/views/livewire/student/library/index.blade.php`

- [ ] **Step 1: Create LibraryTable**

```php
<?php

namespace App\Livewire\Student;

use App\Models\Resource;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class LibraryTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public string $typeFilter = '';
    public ?int $courseFilter = null;
    public ?int $teacherFilter = null;

    public function toggleBookmark(int $resourceId): void
    {
        $user = auth()->user();
        $existing = $user->bookmarks()->where('resource_id', $resourceId)->first();

        if ($existing) {
            $existing->delete();
            $this->success('Bookmark removed.');
        } else {
            $user->bookmarks()->create(['resource_id' => $resourceId]);
            $this->success('Resource bookmarked.');
        }
    }

    public function render()
    {
        $user = auth()->user();
        $bookmarkedIds = $user->bookmarks()->pluck('resource_id')->toArray();

        return view('livewire.student.library.library-table', [
            'resources' => Resource::with(['course', 'teacher', 'files'])
                ->where('is_active', true)
                ->where('course.is_published', true)
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")->orWhere('tags', 'like', "%{$this->search}%"))
                ->when($this->typeFilter, fn ($q) => $q->where('file_type', $this->typeFilter))
                ->when($this->courseFilter, fn ($q) => $q->where('course_id', $this->courseFilter))
                ->when($this->teacherFilter, fn ($q) => $q->where('teacher_id', $this->teacherFilter))
                ->latest()
                ->paginate(12),
            'courses' => \App\Models\Course::where('is_published', true)->get(),
            'teachers' => \App\Models\User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->get(),
            'bookmarkedIds' => $bookmarkedIds,
        ]);
    }
}
```

- [ ] **Step 2: Create library-table blade**

```php
<div>
    <div class="flex flex-wrap gap-4 mb-6">
        <x-input placeholder="Search resources..." wire:model.live="search" icon="o-magnifying-glass" class="flex-1" />
        <x-select placeholder="All Types" wire:model.live="typeFilter"
            :options="[['value' => '', 'label' => 'All'], ['value' => 'pdf', 'label' => 'PDF'], ['value' => 'video', 'label' => 'Video'], ['value' => 'image', 'label' => 'Image']]"
            option-value="value" option-label="label" />
        <x-select placeholder="All Courses" wire:model.live="courseFilter"
            :options="$courses->pluck('title', 'id')->toArray()" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($resources as $resource)
            <x-card class="bg-base-100 shadow-md">
                <div class="flex items-center gap-2 mb-2">
                    <x-badge :value ucfirst($resource->file_type) />
                    @if(in_array($resource->id, $bookmarkedIds))
                        <x-badge value="Bookmarked" class="badge-info" />
                    @endif
                </div>
                <h3 class="font-bold text-lg">{{ $resource->title }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ $resource->course->title }}</p>
                <p class="text-sm text-gray-600">{{ Str::limit($resource->description, 100) }}</p>

                <div class="mt-4 flex gap-2">
                    @if($resource->file_type === 'video' && $resource->primaryFile?->youtube_url)
                        <a href="{{ $resource->primaryFile->youtube_url }}" target="_blank"
                            class="btn btn-primary btn-sm">Watch</a>
                    @else
                        <a href="{{ route('student.resources.download', $resource) }}"
                            class="btn btn-primary btn-sm">Download</a>
                    @endif
                    <button wire:click="toggleBookmark({{ $resource->id }})"
                        class="btn btn-ghost btn-sm">
                        {{ in_array($resource->id, $bookmarkedIds) ? 'Unbookmark' : 'Bookmark' }}
                    </button>
                </div>

                <div class="text-xs text-gray-400 mt-2">
                    by {{ $resource->teacher->name }} | {{ $resource->views }} views
                </div>
            </x-card>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No resources found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $resources->links() }}
    </div>
</div>
```

- [ ] **Step 3: Create library index**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Resource Library')] class extends Component {}; ?>

<div>
    <x-header title="Resource Library" separator />

    <livewire:student.library-table />
</div>
```

---

## Task 10: Student — Course Enrollment & Bookmarks

**Covers:** §3.3 Course Management (enrollment), §3.5 Resource Library (bookmarks)

**Files:**
- Create: `app/Livewire/Student/CourseTable.php`
- Create: `resources/views/livewire/student/courses/index.blade.php`
- Create: `app/Livewire/Student/BookmarkTable.php`
- Create: `resources/views/livewire/student/bookmarks/index.blade.php`

- [ ] **Step 1: Create CourseTable for students**

```php
<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class CourseTable extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    public function enroll(Course $course): void
    {
        auth()->user()->enrollments()->create([
            'course_id' => $course->id,
        ]);
        $this->success('Enrolled successfully!');
    }

    public function unenroll(Course $course): void
    {
        auth()->user()->enrollments()->where('course_id', $course->id)->delete();
        $this->success('Unenrolled.');
    }

    public function render()
    {
        $enrolledIds = auth()->user()->enrollments()->pluck('course_id')->toArray();

        return view('livewire.student.courses.course-table', [
            'courses' => Course::with(['subject', 'teacher'])
                ->where('is_published', true)
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
            'enrolledIds' => $enrolledIds,
        ]);
    }
}
```

- [ ] **Step 2: Create course-table blade for students**

```php
<div>
    <div class="mb-4">
        <x-input placeholder="Search courses..." wire:model.live="search" icon="o-magnifying-glass" />
    </div>

    <x-table :headers="['#', 'Title', 'Subject', 'Teacher', 'Grade', 'Status', 'Actions']">
        @foreach($courses as $course)
            <x-table.row>
                <x-table.cell>{{ $course->id }}</x-table.cell>
                <x-table.cell>{{ $course->title }}</x-table.cell>
                <x-table.cell>{{ $course->subject->name }}</x-table.cell>
                <x-table.cell>{{ $course->teacher->name }}</x-table.cell>
                <x-table.cell>{{ $course->grade_level ?? '-' }}</x-table.cell>
                <x-table.cell>
                    @if(in_array($course->id, $enrolledIds))
                        <x-badge value="Enrolled" class="badge-success" />
                    @else
                        <x-badge value="Open" class="badge-info" />
                    @endif
                </x-table.cell>
                <x-table.cell>
                    @if(in_array($course->id, $enrolledIds))
                        <x-button label="Unenroll" wire:click="unenroll({{ $course->id }})" class="btn-ghost btn-sm" />
                    @else
                        <x-button label="Enroll" wire:click="enroll({{ $course->id }})" class="btn-primary btn-sm" />
                    @endif
                </x-table.cell>
            </x-table.row>
        @endforeach
    </x-table>

    {{ $courses->links() }}
</div>
```

- [ ] **Step 3: Create student courses index**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Courses')] class extends Component {}; ?>

<div>
    <x-header title="Available Courses" separator />

    <livewire:student.course-table />
</div>
```

- [ ] **Step 4: Create BookmarkTable**

```php
<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class BookmarkTable extends Component
{
    use WithPagination, Toast;

    public function removeBookmark(int $resourceId): void
    {
        auth()->user()->bookmarks()->where('resource_id', $resourceId)->delete();
        $this->success('Bookmark removed.');
    }

    public function render()
    {
        return view('livewire.student.bookmarks.bookmark-table', [
            'bookmarks' => auth()->user()->bookmarks()
                ->with(['resource.course', 'resource.teacher'])
                ->latest()
                ->paginate(10),
        ]);
    }
}
```

- [ ] **Step 5: Create bookmark-table blade**

```php
<div>
    @forelse($bookmarks as $bookmark)
        <x-card class="mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold">{{ $bookmark->resource->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $bookmark->resource->course->title }}</p>
                    <p class="text-sm text-gray-600">{{ Str::limit($bookmark->resource->description, 100) }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('student.resources.download', $bookmark->resource) }}"
                        class="btn btn-primary btn-sm">Download</a>
                    <x-button icon="o-trash" wire:click="removeBookmark({{ $bookmark->resource_id }})"
                        class="btn-ghost btn-sm text-error" />
                </div>
            </div>
        </x-card>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-500">No bookmarks yet.</p>
        </div>
    @endforelse

    {{ $bookmarks->links() }}
</div>
```

- [ ] **Step 6: Create student bookmarks index**

```php
<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('My Bookmarks')] class extends Component {}; ?>

<div>
    <x-header title="My Bookmarks" separator />

    <livewire:student.bookmark-table />
</div>
```

---

## Task 11: Dashboard System

**Covers:** §3.7 Dashboard System

**Files:**
- Modify: `resources/views/livewire/dashboard.blade.php`

- [ ] **Step 1: Update dashboard with role-based views**

Replace `resources/views/livewire/dashboard.blade.php` with:

```php
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
```

---

## Task 12: Routes

**Covers:** §3.1 Authentication & Roles, all route definitions

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Update routes**

Replace `routes/web.php` with:

```php
<?php

use App\Livewire\Admin\UserForm;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;

// Landing page
Volt::route('/', 'landing')->name('landing');

// Authentication routes
Route::middleware('guest')->group(function () {
    Volt::route('/login', 'auth.login')->name('login');
    Volt::route('/register', 'auth.register')->name('register');
    Volt::route('/forgot-password', 'auth.forgot-password')->name('password.request');
    Volt::route('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
});

// Email verification
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

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('/dashboard', 'dashboard')->name('dashboard')->middleware('permission:access_dashboard');
    Volt::route('/profile', 'profile')->name('profile');
    Volt::route('/logout', 'auth.logout')->name('logout');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Users
        Volt::route('/users', 'admin.users.index')->name('users.index');
        Volt::route('/users/create', 'admin.users.create-or-edit')->name('users.create');
        Volt::route('/users/{id}/edit', 'admin.users.create-or-edit')->name('users.edit');

        // Subjects
        Volt::route('/subjects', 'admin.subjects.index')->name('subjects.index');
        Volt::route('/subjects/create', 'admin.subjects.create-or-edit')->name('subjects.create');
        Volt::route('/subjects/{id}/edit', 'admin.subjects.create-or-edit')->name('subjects.edit');

        // Courses
        Volt::route('/courses', 'admin.courses.index')->name('courses.index');
        Volt::route('/courses/create', 'admin.courses.create-or-edit')->name('courses.create');
        Volt::route('/courses/{id}/edit', 'admin.courses.create-or-edit')->name('courses.edit');
    });

    // Teacher routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Volt::route('/resources', 'teacher.resources.index')->name('resources.index');
        Volt::route('/resources/create', 'teacher.resources.create-or-edit')->name('resources.create');
        Volt::route('/resources/{id}/edit', 'teacher.resources.create-or-edit')->name('resources.edit');
    });

    // Student routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Volt::route('/library', 'student.library.index')->name('library.index');
        Volt::route('/courses', 'student.courses.index')->name('courses.index');
        Volt::route('/bookmarks', 'student.bookmarks.index')->name('bookmarks.index');

        // Download resource
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
```

---

## Task 13: Sidebar Navigation

**Covers:** §3.1 Authentication & Roles (navigation)

**Files:**
- Modify: `resources/views/livewire/sidebar.blade.php`

- [ ] **Step 1: Update sidebar**

Replace `resources/views/livewire/sidebar.blade.php` with:

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public function render()
    {
        return view('livewire.sidebar');
    }
}; ?>

<div>
    {{-- Logo --}}
    <a href="/" class="text-xl font-bold">Learning Portal</a>

    <x-menu-item title="Dashboard" icon="o-home" link="/dashboard" />

    @if(auth()->user()->hasRole('admin'))
        <x-menu-item title="Users" icon="o-users" link="/admin/users" />
        <x-menu-item title="Subjects" icon="o-bookmark" link="/admin/subjects" />
        <x-menu-item title="Courses" icon="o-academic-cap" link="/admin/courses" />
    @endif

    @if(auth()->user()->hasRole('teacher'))
        <x-menu-item title="My Resources" icon="o-document-duplicate" link="/teacher/resources" />
    @endif

    @if(auth()->user()->hasRole('student'))
        <x-menu-item title="Library" icon="o-library" link="/student/library" />
        <x-menu-item title="Courses" icon="o-academic-cap" link="/student/courses" />
        <x-menu-item title="Bookmarks" icon="o-bookmark" link="/student/bookmarks" />
    @endif
</div>
```

---

## Task 14: Verification

**Covers:** All

- [ ] **Step 1: Run migrations and seed**

```bash
php artisan migrate:fresh --seed
```

Expected: All migrations run, seeders complete.

- [ ] **Step 2: Create storage link**

```bash
php artisan storage:link
```

Expected: Storage link created.

- [ ] **Step 3: Run type check**

```bash
./vendor/bin/phpstan analyse
```

Expected: No errors.

- [ ] **Step 4: Run tests**

```bash
php artisan test
```

Expected: Tests pass.

- [ ] **Step 5: Verify routes**

```bash
php artisan route:list
```

Expected: All routes registered correctly.

---

## Task 15: Commit

- [ ] **Step 1: Stage and commit all changes**

```bash
git add -A
git commit -m "feat: complete learning portal - models, migrations, seeders, Livewire components, dashboards, and routes"
```
