<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button,
    Column,
    Exportable,
    Footer,
    Header,
    PowerGrid,
    PowerGridComponent,
    PowerGridEloquent
};

final class UserTable extends PowerGridComponent
{
    use ActionButton;

    public string  $primaryKey = 'users.id';

    public string $sortField = 'users.id';
    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder|null
     */
    public function datasource(): ?Builder
    {
        return User::with('roles');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('email')
            ->addColumn(
                'roles.name',
                function (User $model) {
                    $model->load('roles');

                    return e($model->roles[0]->name);
                }
            )
            ->addColumn(
                'created_at_formatted',
                fn(User $model) => Carbon::parse($model->created_at)->format('jS M, y')
            );
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),

            Column::make('NAME', 'name')
                ->sortable()
                ->headerAttribute(styleAttr: "width: 200px"),

            Column::make('EMAIL', 'email')
                ->sortable(),

            Column::make('ROLE', 'roles.name')
                ->headerAttribute(styleAttr: "width: 100px"),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->sortable()
                ->headerAttribute(styleAttr: "width: 150px"),


        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid User Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
            Button::make('view', "<i class='fa-solid fa-eye'></i>")
                ->class('btn btn-sm btn-outline-primary w-100 rounded-0 my-1')
                ->route('users.show', ['user' => 'id']),

            Button::make('destroy', "<i class='fa-solid fa-trash'></i>")
                ->class('btn btn-sm btn-outline-warning w-100 rounded-0 my-1')
                ->route('users.destroy', ['user' => 'id'])
                ->method('delete')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid User Action Rules.
     *
     * @return array<int, RuleActions>
     */

    public function actionRules(): array
    {
        return [
            //Hide button edit for ID 1
            Rule::button('destroy')
                ->when(fn($user) => $user->id === auth()->id())
                ->hide(),
        ];
    }
}
