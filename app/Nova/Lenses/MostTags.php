<?php

namespace App\Nova\Lenses;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class MostTags extends Lens
{
    /**
     * The columns that should be searched.
     */
    public static $search = [];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param Builder $query
     */
    public static function query(LensRequest $request, $query): Builder
    {
        return $request->withOrdering($request->withFilters(
            $query->withCount('tags')
                ->orderBy('tags_count', 'desc')
        ));
    }

    /**
     * Get the fields available to the lens.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Title'),
            Number::make('# Tags', 'tags_count'),
        ];
    }

    /**
     * Get the cards available on the lens.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     */
    public function actions(NovaRequest $request): array
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     */
    public function uriKey(): string
    {
        return 'most-tags';
    }
}
