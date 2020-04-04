<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;

use Illuminate\Http\Request;
use TaylorNetwork\LaravelNexmo\Models\Ivr;
use TaylorNetwork\LaravelNexmo\NccoBuilder;

class CallController extends ApiController
{
    public function answerWithIvr(Ivr $ivr, Request $request)
    {
        return response()->json($ivr->build());
    }

    public function answer(Request $request)
    {
        if(class_exists('App\\Http\\Controllers\\Api\\CallController')) {
            try {
                return app('App\\Http\\Controllers\\Api\\CallController')->answer($request);
            } catch (\Exception $exception) {
                //
            }
        }

        try {
            return $this->answerWithIvr(Ivr::firstOrFail(), $request);
        } catch (\Exception $exception) {
            //
        }

        return NccoBuilder::talk('This answer webhook has not been properly set up.')
            ->talk('Please consult the Taylor Network, Laravel Nexmo, Read Me.');
    }
}