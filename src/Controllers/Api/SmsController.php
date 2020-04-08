<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;

use Illuminate\Http\Request;
use TaylorNetwork\LaravelNexmo\Models\Sms;

class SmsController extends ApiController
{
    public function handleInboundMessage(Request $request)
    {
        Sms::storeInboundMessage($request);
        return response('OK', 200);
    }

    public function send(Sms $sms, Request $request)
    {
        $sms->send();
        return response('OK', 200);
    }

    public function handleOutboundMessage(Request $request)
    {
        return redirect()->route('sms.send', Sms::storeOutboundMessage($request));
    }
}