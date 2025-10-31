<div>
    <x-card-link title="Create Category">
        <x-form wire:submit.prevent='create'>
            <x-input label="Name" wire:model.live="form.name" />
            <x-select label="Status" wire:model.live="form.status" :options="$statusOptions" option-value="value"
                option-label="name" placeholder="Select an option " />

            <x-card-footer back-route="{{ route('category.index') }}" back-label="Back"  button-label="Save"
                spinner="saving"  />



        </x-form>
    </x-card-link>

</div>
