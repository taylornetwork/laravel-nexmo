<?php


namespace TaylorNetwork\LaravelNexmo\Models;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use TaylorNetwork\LaravelNexmo\Contracts\RespondsWithJsonNcco;
use TaylorNetwork\LaravelNexmo\NccoBuilder;
use TaylorNetwork\LaravelNexmo\Traits\SimpleJsonResponse;

class Ivr extends Model implements RespondsWithJsonNcco
{
    use SimpleJsonResponse;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function ivrSteps()
    {
        return $this->hasMany(IvrStep::class)->orderBy('order', 'asc');
    }

    public function build(Request $request = null): array
    {
        return $this->builder($request)->getNcco();
    }

    public function builder(Request $request = null): NccoBuilder
    {
        if($request === null) {
            $request = request();
        }

        $builder = new NccoBuilder();

        foreach($this->ivrSteps as $step) {
            $step->handleRequestCallback($request, function (IvrStep $step) use ($builder) {
                $builder->addAction($step->action, $step->options);
            });
        }

        return $builder;
    }

    public function bootSlugify()
    {
        static::saving(function (Ivr $ivr) {
            if(!isset($ivr->attributes['slug']) || empty($ivr->attributes['slug'])) {
                $ivr->attributes['slug'] = Str::kebab($ivr->attributes['slug']);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @inheritDoc
     */
    public function buildResponse(int $httpStatus = 200): JsonResponse
    {
        return response()->json($this->build(), $httpStatus);
    }


}