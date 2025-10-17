<?php

namespace App\Services\Formation;

use App\Models\Formation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Shared behaviour for listing formations regardless of the caller type.
 * Sub-classes can specialise the query through the decorateQuery hook.
 */
abstract class BaseFormationService
{
    /**
     * Return a complete list of formations after applying the provided filters.
     *
     * Supported $options keys:
     *  - search: string|null
     *  - order_by: string|null
     *  - direction: string (asc|desc)
     */
    public function list(array $options = []): Collection
    {
        return $this->prepareQuery($options)->get();
    }

    /**
     * Paginate formations while applying the same filters as the list method.
     *
     * Supported $options keys:
     *  - search: string|null
     *  - order_by: string|null
     *  - direction: string (asc|desc)
     *  - per_page: int
     */
    public function paginate(array $options = []): LengthAwarePaginator
    {
        $perPage = (int) ($options['per_page'] ?? 15);

        return $this->prepareQuery($options)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Base query before applying caller-specific constraints.
     */
    protected function baseQuery(): Builder
    {
        return Formation::query();
    }

    /**
     * Hook for children to add joins, counts, etc.
     */
    protected function decorateQuery(Builder $query, array $options): Builder
    {
        return $query;
    }

    private function prepareQuery(array $options): Builder
    {
        $query = $this->decorateQuery($this->baseQuery(), $options);

        if (!empty($options['search'])) {
            $search = $options['search'];
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $orderBy = $options['order_by'] ?? 'title';
        $direction = $options['direction'] ?? 'asc';

        if ($orderBy) {
            $query->orderBy($orderBy, $direction);
        }

        return $query;
    }
}

