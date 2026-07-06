<div>
    @forelse($bookmarks as $bookmark)
        <x-card class="mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold">{{ $bookmark->resource->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $bookmark->resource->course->title }}</p>
                    <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($bookmark->resource->description, 100) }}</p>
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
