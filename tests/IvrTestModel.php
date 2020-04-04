<?php


namespace TaylorNetwork\LaravelNexmo\Tests;

use TaylorNetwork\LaravelNexmo\Models\Ivr;
use TaylorNetwork\LaravelNexmo\Models\IvrStep;

class IvrTestModel extends Ivr
{
    public $ivrSteps;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->ivrSteps = [
            new IvrStep([ 'action' => 'talk', 'options' => ['text' => 'Hello!'] ]),
            new IvrStep([ 'action' => 'talk', 'options' => ['voiceName' => 'Mathieu', 'text' => 'This is number 2!'] ]),
        ];

    }
}