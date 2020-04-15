<?php


namespace TaylorNetwork\LaravelNexmo\Controllers;

use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Support\Facades\Config;

abstract class Controller extends LaravelController
{
    public function getAppController()
    {
        $controller = Config::get('ncco.controller_namespace').class_basename($this);

        if(class_exists($controller)) {
            return app($controller);
        }

        return false;
    }

    public function controllerResponse(string $method, array $arguments = [])
    {
        $controller = $this->getAppController();
        if($controller && method_exists($controller, $method)) {
            return $controller->$method(...$arguments);
        }

        return redirect()->route('home');
    }
}