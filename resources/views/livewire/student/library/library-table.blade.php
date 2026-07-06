<div>
    <div class="flex flex-wrap gap-4 mb-6">
        <input type="text" placeholder="Search resources..." wire:model.live="search" class="input input-bordered flex-1" />
        <select wire:model.live="typeFilter" class="select select-bordered">
            <option value="">All Types</option>
            <option value="pdf">PDF</option>
            <option value="video">Video</option>
            <option value="image">Image</option>
        </select>
        <select wire:model.live="courseFilter" class="select select-bordered">
            <option value="">All Courses</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($resources as $resource)
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="badge">{{ ucfirst($resource->file_type) }}</span>
                        @if(in_array($resource->id, $bookmarkedIds))
                            <span class="badge badge-info">Bookmarked</span>
                        @endif
                    </div>
                    <h3 class="card-title">{{ $resource->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $resource->course->title }}</p>
                    <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($resource->description, 100) }}</p>

                    <div class="card-actions justify-end mt-4">
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
                </div>
            </div>
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
