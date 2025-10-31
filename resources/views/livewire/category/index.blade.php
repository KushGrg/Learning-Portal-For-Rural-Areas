<div>
    <x-card-link title="Category" link="{{ route('category.create') }}" icon="o-plus" text="Add Category"
        permission="create_category">
    <div>
    <livewire:category.category-table />
</div>

    </x-card-link>
</div>
