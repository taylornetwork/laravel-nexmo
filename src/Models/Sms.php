<?php


namespace TaylorNetwork\LaravelNexmo\Models;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Nexmo\Client;
use Nexmo\Message\InboundMessage;

class Sms extends Model
{
    protected $fillable = [
        'to', 'from', 'message_id', 'network', 'text', 'received_at', 'sent_at', 'price',
    ];

    protected $dates = [ 'received_at', 'sent_at' ];

    public static function storeInboundMessage(Request $request)
    {
        $message = InboundMessage::createFromGlobals();

        return static::create([
            'from' => $message->getFrom(),
            'to' => $message->getTo(),
            'message_id' => $message->getMessageId(),
            'network' => $message->getNetwork(),
            'text' => $message->getBody(),
            'received_at' => Carbon::now(),
        ]);
    }

    public static function storeOutboundMessage(Request $request)
    {
        return static::create([
            'from' =>  Config::get('ncco.number'),
            'to' => $request->to,
            'text' => $request->text,
        ]);
    }

    public function getIsSentAttribute()
    {
        return $this->sent_at !== null && $this->received_at === null;
    }

    public function getDirectionAttribute()
    {
        return $this->isInbound ? 'Inbound' : 'Outbound';
    }

    public function getIsInboundAttribute()
    {
        return $this->received_at !== null;
    }

    public function getIsOutboundAttribute()
    {
        return $this->received_at === null;
    }

    public static function compose(string $to, string $text): Sms
    {
        return static::create([
            'to' => $to,
            'text' => $text,
            'from' => Config::get('ncco.number'),
        ]);
    }

    public function reply(string $text)
    {
        if($this->isOutbound) {
            return false;
        }

        return static::compose($this->from, $text);
    }

    public function send()
    {
        if($this->isSent || $this->isInbound) {
            return false;
        }

        try {
            $message = app(Client::class)->message()->send($this->only(['to','from','text']));
        } catch (\Exception $exception) {
            return false;
        }

        $this->update([
            'sent_at' => Carbon::now(),
            'price' => $message->getPrice(),
            'message_id' => $message->getMessageId(),
            'network' => $message->getNetwork(),
        ]);

        return true;
    }
}