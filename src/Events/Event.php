<?php


namespace TaylorNetwork\LaravelNexmo\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

abstract class Event implements ShouldBroadcast
{
    use SerializesModels;
}