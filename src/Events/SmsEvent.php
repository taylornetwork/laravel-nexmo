<?php


namespace TaylorNetwork\LaravelNexmo\Events;

use Illuminate\Broadcasting\Channel;
use TaylorNetwork\LaravelNexmo\Models\Sms;

abstract class SmsEvent extends Event
{
    public $direction;

    public $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function broadcastOn()
    {
        return new Channel('messenger.' . $this->direction . '.' . $this->sms->from);
    }
}