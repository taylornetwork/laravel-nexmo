<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;


use Illuminate\Http\Request;
use TaylorNetwork\LaravelNexmo\Models\Ivr;
use TaylorNetwork\LaravelNexmo\Models\IvrStep;

class IvrStepController extends ApiController
{
    public function index(Ivr $ivr)
    {
        return $ivr->ivrSteps;
    }

    public function store(Ivr $ivr, Request $request)
    {
        $ivr->ivrSteps()->create($request->only(['action','options','order']));
        return response('OK');
    }

    public function show(Ivr $ivr, IvrStep $ivrStep)
    {
        return $ivrStep;
    }

    public function update(Ivr $ivr, IvrStep $ivrStep, Request $request)
    {
        $ivrStep->update($request->only(['action','options','order']));
        return response('OK');
    }

    public function destroy(Ivr $ivr, IvrStep $ivrStep)
    {
        $ivrStep->delete();
        return response('OK');
    }
}