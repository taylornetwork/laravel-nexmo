<?php


namespace TaylorNetwork\LaravelNexmo\Events;

use Illuminate\Broadcasting\Channel;
use TaylorNetwork\LaravelNexmo\Models\Sms;

abstract class SmsEvent extends Event
{
    public $direction;

    public $sms;

    public $contact;

    public function __construct(Sms $sms)
    {
        $this->direction = strtolower($this->direction);
        $this->sms = $sms;
        $this->contact = $this->direction === 'outbound' ? $this->sms->to : $this->sms->from;
    }

    public function getChannel()
    {
        return 'messenger.'.$this->contact;
    }

    public function broadcastAs()
    {
        return $this->direction . '.message';
    }
}