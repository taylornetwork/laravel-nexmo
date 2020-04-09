<?php


namespace TaylorNetwork\LaravelNexmo\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

abstract class Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function broadcastOn()
    {
        $channel = $this->getChannel();

        if(is_array($channel)) {
            return $channel;
        }

        return [$channel];
    }

    abstract public function getChannel();
}