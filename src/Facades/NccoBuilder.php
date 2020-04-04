<?php


namespace TaylorNetwork\LaravelNexmo\Facades;

use Illuminate\Support\Facades\Facade;

class NccoBuilder extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'NccoBuilder';
    }
}