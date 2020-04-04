<?php


namespace TaylorNetwork\LaravelNexmo\Tests;


use TaylorNetwork\LaravelNexmo\NccoBuilder;

class BuilderNoConfig extends NccoBuilder
{
    protected $actionDefaultsFromConfig = false;
}