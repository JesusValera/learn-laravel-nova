<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PostCategories extends Filter
{
    /**
     * The filter's component.
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param Builder $query
     */
    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('category', $value);
    }

    /**
     * Get the filter's available options.
     */
    public function options(NovaRequest $request): array
    {
        return [
            'tutorials' => 'Tutorials',
            'news' => 'News',
        ];
    }
}
