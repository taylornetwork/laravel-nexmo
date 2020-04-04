<?php


namespace TaylorNetwork\LaravelNexmo\Models;

use Illuminate\Support\Str;
use TaylorNetwork\LaravelNexmo\NccoBuilder;

class Ivr extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function ivrSteps()
    {
        return $this->hasMany(IvrStep::class)->orderBy('order', 'asc');
    }

    public function build(bool $asJson = false)
    {
        $builder = new NccoBuilder();

        foreach($this->ivrSteps as $step) {
            $builder->addAction($step->action, $step->options);
        }

        return $asJson ? $builder->getJsonNcco() : $builder->getNcco();
    }

    public function bootSlugify()
    {
        static::saving(function (Ivr $ivr) {
            if(!isset($ivr->attributes['slug']) || empty($ivr->attributes['slug'])) {
                $ivr->attributes['slug'] = Str::kebab($ivr->attributes['slug']);
            }
        });
    }
}