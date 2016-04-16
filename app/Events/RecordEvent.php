<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecordEvent extends Event
{
    use SerializesModels;

    public $method;

    public $params;

    /**
     * RecordEvent constructor.
     * @param string $method
     * @param array $params
     */
    public function __construct($method , $params = [])
    {
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
