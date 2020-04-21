<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;


use Illuminate\Http\Request;
use TaylorNetwork\LaravelNexmo\Models\Ivr;

class IvrController extends ApiController
{
    public function index()
    {
        return Ivr::all();
    }

    public function store(Request $request)
    {
        Ivr::create($request->only(['name', 'slug']));
        return response('OK');
    }

    public function show(Ivr $ivr)
    {
        return $ivr;
    }

    public function update(Ivr $ivr, Request $request)
    {
        $ivr->update($request->only(['name','slug']));
        return response('OK');
    }

    public function destroy(Ivr $ivr)
    {
        foreach($ivr->ivrSteps as $ivrStep) {
            $ivrStep->delete();
        }

        $ivr->delete();
        return response('OK');
    }
}