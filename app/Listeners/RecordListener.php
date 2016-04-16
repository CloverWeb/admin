<?php

namespace App\Listeners;

use App\Events\RecordEvent;
use App\Models\Admin\AdminBehaviorRecord;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordListener
{
    /**
     * @var AdminBehaviorRecord $model
     */
    protected $model;

    /**
     * RecordListener constructor.
     * @param AdminBehaviorRecord $record
     */
    public function __construct(AdminBehaviorRecord $record)
    {
        $this->model = $record;
    }

    /**
     * @param RecordEvent $event
     * @return mixed
     * @throws \Exception
     */
    public function handle(RecordEvent $event)
    {
        if (method_exists($this, $event->method)) {
            return call_user_func_array([$this , $event->method] , $event->params);
        }

        throw new \Exception('method not found');
    }
}
