<?php


namespace TaylorNetwork\LaravelNexmo\Events;


class OutboundMessageSent extends SmsEvent
{
    public $direction = 'outbound';
}