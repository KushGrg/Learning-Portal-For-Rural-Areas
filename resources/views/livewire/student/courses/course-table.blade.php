<div>
    <div class="mb-4">
        <input type="text" placeholder="Search courses..." wire:model.live="search" class="input input-bordered w-full max-w-xs" />
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->subject->name }}</td>
                        <td>{{ $course->teacher->name }}</td>
                        <td>{{ $course->grade_level ?? '-' }}</td>
                        <td>
                            @if(in_array($course->id, $enrolledIds))
                                <span class="badge badge-success">Enrolled</span>
                            @else
                                <span class="badge badge-info">Open</span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($course->id, $enrolledIds))
                                <button wire:click="unenroll({{ $course->id }})" class="btn btn-ghost btn-sm">Unenroll</button>
                            @else
                                <button wire:click="enroll({{ $course->id }})" class="btn btn-primary btn-sm">Enroll</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $courses->links() }}
    </div>
</div>
