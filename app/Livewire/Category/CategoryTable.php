<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class CategoryTable extends PowerGridComponent
{
    use Toast;
    public string $tableName = 'category-table';
    public array $name;
    public array $status;
    public bool $showErrorBag = true;
    public function setUp(): array
    {

        $this->persist(['columns', 'is_active'], prefix: auth()->id ?? '');

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('is_active')
            ->add('created_by');
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('S.no')
                ->index(),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(),
            Column::make('Status', 'is_active')
                ->sortable()
                ->searchable()
                ->toggleable(hasPermission: true),

        ];
    }

    protected function rules()
    {
        return [
            'name.*' => ['required'],
            'status.*' => ['required', 'boolean'],
        ];
    }
    public function onUpdatedEditable(int|string $id, string $field, string $value): void
    {   
        try {
            $this->withValidator(function (Validator $validator) use ($id, $field) {
                if ($validator->errors()->isNotEmpty()) {
                    $this->dispatch('toggle-' . $field . '-' . $id);
                    $this->addError($field, $validator->errors()->first($field));

                }
            })->validate();
            Category::query()->find($id)->update([$field => $value]);
            $this->success('Category updated successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to update category.');
        }

    }
    public function onUpdatedToggleable(string $id, string $field, string $value): void
    {
        Category::query()->find($id)->update([$field => $value]);
        $this->skipRender();

    }
    public function filters(): array
    {
        return [

            //  Filter::boolean('is_active', 'inactive')
            //     // ->label('Active', 'Inactive'),
        ];
    }
}
