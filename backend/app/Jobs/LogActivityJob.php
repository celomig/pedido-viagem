<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\LogsActivity;

class LogActivityJob implements ShouldQueue
{
    use Queueable;

    private $event;
    private $model;

    /**
     * Create a new job instance.
     */
    public function __construct(string $event, $model)
    {
        $this->event = $event;
        $this->model = $model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        LogsActivity::create([
            'model' => get_class($this->model),
            'model_id' => $this->model->id,
            'event' => $this->event,
            'data' => $this->event === 'updated' ? json_encode($this->model->getChanges()) : $this->model->toJson(),
        ]);
    }
}
