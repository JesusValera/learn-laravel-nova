<?php

namespace App\Nova;

use App\Nova\Actions\PublishPost;
use App\Nova\Filters\PostCategories;
use App\Nova\Filters\PostPublished;
use App\Nova\Lenses\MostTags;
use App\Nova\Metrics\PostCount;
use App\Nova\Metrics\PostsPerCategory;
use App\Nova\Metrics\PostsPerDay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Post>
     */
    public static string $model = \App\Models\Post::class;

    public function title(): string
    {
        return $this->title . ' - ' . $this->category;
    }

    public function subtitle(): string
    {
        return 'Author: ' . $this->user->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'body',
    ];

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query->where('user_id', $request->user()->id);
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Title')->rules('required'),
            Trix::make('Body')->rules('required'),
            DateTime::make('Publish At')->rules('after_or_equal:today')->hideFromIndex(),
            DateTime::make('Publish Until')->rules('after_or_equal:publish_at')->hideFromIndex(),
            Boolean::make('Is Published')->canSee(fn(Request $request) => true),
            Select::make('Category')->rules('required')->options([
                'tutorials' => 'Tutorials',
                'news' => 'News',
            ])->hideWhenUpdating(),
            BelongsTo::make('User')->nullable()->rules('required'),
            BelongsToMany::make('Tags'),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [
            (new PostCount())->width('1/2'),
            (new PostsPerCategory())->width('1/2'),
            (new PostsPerDay())->width('full'),
        ];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new PostPublished(),
            new PostCategories(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [
            new MostTags(),
        ];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [
            (new PublishPost())
                ->canSee(fn(Request $request) => true)
                ->canRun(fn(Request $request) => true),
        ];
    }
}
