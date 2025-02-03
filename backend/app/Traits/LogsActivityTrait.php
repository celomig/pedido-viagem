<?php

namespace App\Traits;

use App\Jobs\LogActivityJob;

trait LogsActivityTrait
{
    public static function bootLogsActivityTrait()
    {
        static::created(function ($model) {
            dispatch(new LogActivityJob('created', $model))->onQueue('low');
        });

        static::updated(function ($model) {
            dispatch(new LogActivityJob('updated', $model))->onQueue('low');
        });

        static::deleted(function ($model) {
            dispatch(new LogActivityJob('deleted', $model))->onQueue('low');
        });
    }
}
