<div>
    <div class="flex flex-wrap gap-4 mb-4">
        <input type="text" placeholder="Search resources..." wire:model.live="search" class="input input-bordered w-full max-w-xs" />
        <select wire:model.live="typeFilter" class="select select-bordered">
            <option value="">All Types</option>
            <option value="pdf">PDF</option>
            <option value="video">Video</option>
            <option value="image">Image</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Type</th>
                    <th>Views</th>
                    <th>Downloads</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                    <tr>
                        <td>{{ $resource->id }}</td>
                        <td>{{ $resource->title }}</td>
                        <td>{{ $resource->course->title }}</td>
                        <td>
                            <span class="badge">{{ ucfirst($resource->file_type) }}</span>
                        </td>
                        <td>{{ $resource->views }}</td>
                        <td>{{ $resource->downloads }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('teacher.resources.edit', $resource) }}" class="btn btn-ghost btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <button wire:click="deleting({{ $resource->id }})" wire:confirm="Are you sure?" class="btn btn-ghost btn-sm text-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No resources found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $resources->links() }}
    </div>
</div>
