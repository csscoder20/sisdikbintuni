<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasActivityLog
{
    public static function bootHasActivityLog(): void
    {
        static::created(function (Model $model) {
            static::recordActivity($model, 'created', "Created " . class_basename($model));
        });

        static::updated(function (Model $model) {
            $changes = [
                'before' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'after' => $model->getDirty(),
            ];

            // Don't log if only timestamps changed
            if (empty(array_diff(array_keys($changes['after']), ['updated_at', 'created_at']))) {
                return;
            }

            static::recordActivity($model, 'updated', "Updated " . class_basename($model), $changes);
        });

        static::deleted(function (Model $model) {
            static::recordActivity($model, 'deleted', "Deleted " . class_basename($model));
        });
    }

    protected static function recordActivity(Model $model, string $event, string $description, array $properties = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'description' => $description,
            'subject_type' => get_class($model),
            'subject_id' => $model->getKey(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
