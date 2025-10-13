<?php

namespace App\Livewire\PowerGrid;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class UserTableInTeam extends PowerGridComponent
{
    use AuthorizesRequests;

    public string $tableName = 'user-table-qfkn8u-table';

    /** Livewire va hydrater cette propriété depuis :team="$team" */
    public Team $team;

    public function booted(): void
    {
        // sécurité (Gate/Policy): l'utilisateur doit appartenir à l'équipe
        $this->authorize('access-team', $this->team);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): \Illuminate\Database\Eloquent\Builder
    {
        // Filtre par l’équipe courante via whereHas — retourne bien un Eloquent\Builder
        return User::query()
            ->whereHas('teams', fn ($q) => $q->whereKey($this->team->getKey()));
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('created_at')
            ->add('created_at_formatted', fn (User $u) => Carbon::parse($u->created_at)->format('d/m/Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')->sortable()->searchable(),
            Column::make('Email', 'email')->sortable()->searchable(),
            Column::make('Created at', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Action'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $user = User::find($rowId);
        if ($user) {
            // Retirer l'utilisateur de l'équipe courante
            $this->team->users()->detach($user);
        }
    }

    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('Retirer: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id]),

            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id]),
        ];
    }
}
