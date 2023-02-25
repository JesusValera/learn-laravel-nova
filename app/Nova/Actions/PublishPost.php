<?php

namespace App\Nova\Actions;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PublishPost extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param Collection<Post> $models
     */
    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $model) {
            $model->update([
                'is_published' => true,
            ]);
        }

        return Action::message($fields->message);
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Message'),
        ];
    }
}
