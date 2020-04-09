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
        $this->sms = $sms;
        $this->contact = strtolower($this->direction) === 'outbound' ? $this->sms->to : $this->sms->from;
    }

    public function getChannel()
    {
        return 'messenger.'.$this->direction.'.'.$this->contact;
    }
}