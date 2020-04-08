<?php


namespace TaylorNetwork\LaravelNexmo\Events;

class InboundMessageReceived extends SmsEvent
{
    public $direction = 'inbound';
}