<?php


namespace TaylorNetwork\LaravelNexmo\Models;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Nexmo\Client;
use Nexmo\Message\InboundMessage;
use Nexmo\Message\Text;

class Sms extends Model
{
    protected $fillable = [
        'to', 'from', 'message_id', 'account_id', 'network', 'body', 'received_at', 'sent_at',
    ];

    protected $dates = [ 'received_at', 'sent_at' ];

    public static function storeInboundMessage(Request $request)
    {
        $message = InboundMessage::createFromGlobals();

        return static::create([
            'from' => $message->getFrom(),
            'to' => $message->getTo(),
            'message_id' => $message->getMessageId(),
            'account_id' => $message->getAccountId(),
            'network' => $message->getNetwork(),
            'body' => $message->getBody(),
            'received_at' => Carbon::now(),
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

    public function send()
    {
        try {
            $message = app(Client::class)->message()->send($this->only(['to','from','body']));
        } catch (\Exception $exception) {
            return false;
        }

        $this->update([
            'sent_at' => Carbon::now(),
            'price' => $message->getPrice() ?? null,
            'message_id' => $message->getMessageId() ?? null,
            'account_id' => $message->getAccountId() ?? null,
            'network' => $message->getNetwork() ?? null,
        ]);

        return true;
    }
}