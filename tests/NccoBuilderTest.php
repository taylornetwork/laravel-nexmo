<?php

namespace TaylorNetwork\LaravelNexmo\Tests;

use Orchestra\Testbench\TestCase;
use TaylorNetwork\LaravelNexmo\NccoBuilder;
use TaylorNetwork\LaravelNexmo\NexmoServiceProvider;

class NccoBuilderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [NexmoServiceProvider::class];
    }

    public function testTalk()
    {
        $builder = new NccoBuilder();
        $builder->talk('Hi!');
        $this->assertEquals([[
            'action' => 'talk',
            'text' => 'Hi!',
            'voiceName' => 'Joey'
        ]], $builder->getNcco());
    }

    public function testTalkWithOptionsOverride()
    {
        $builder = new NccoBuilder();
        $builder->talk('Hi!', [ 'voiceName' => 'Justin' ]);
        $this->assertEquals([[
            'action' => 'talk',
            'text' => 'Hi!',
            'voiceName' => 'Justin'
        ]], $builder->getNcco());
    }

    public function testTalkWithExtraOptions()
    {
        $builder = new NccoBuilder();
        $builder->talk('Hi!', [ 'bargeIn' => true ]);
        $this->assertEquals([[
            'action' => 'talk',
            'text' => 'Hi!',
            'voiceName' => 'Joey',
            'bargeIn' => true
        ]], $builder->getNcco());
    }

    public function testTalkNoConfig()
    {
        $builder = new BuilderNoConfig();
        $builder->talk('Hi!');
        $this->assertEquals([[
            'action' => 'talk',
            'text' => 'Hi!',
        ]], $builder->getNcco());
    }

    public function testIvr()
    {
        $ivr = new IvrTestModel();
        $this->assertEquals([
            [
                'action' => 'talk',
                'text' => 'Hello!',
                'voiceName' => 'Joey',
            ],
            [
                'action' => 'talk',
                'text' => 'This is number 2!',
                'voiceName' => 'Mathieu',
            ],
        ], $ivr->build());
    }
}
