<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use TaylorNetwork\LaravelNexmo\Models\Ivr;
use TaylorNetwork\LaravelNexmo\Facades\NccoBuilder;

class CallController extends ApiController
{
    public function answerWithIvr(Ivr $ivr, Request $request): JsonResponse
    {
        return $ivr->respond();
    }

    public function answer(Request $request): JsonResponse
    {
        $appController = Config::get('ncco.call_controller_class');
        $answerMethod = Config::get('ncco.answer_method', 'answer');

        if(!class_exists($appController)) {
            $appController = Config::get('ncco.api_controller_namespace') . $appController;
        }

        if(class_exists($appController)) {
            try {
                return app($appController)->$answerMethod($request);
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
            ->talk('Please consult the Taylor Network Laravel Nexmo Read Me.')
            ->respond();
    }
}