<?php


namespace TaylorNetwork\LaravelNexmo\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class Call extends Model
{
    protected $fillable = [
        'conversation_uuid',
        'from',
        'to',
        'started',
        'answered',
        'completed',
        'price',
    ];

    protected $dates = [
        'started',
        'answered',
        'completed'
    ];

    protected $appends = [
        'status',
        'direction',
        'isCompleted',
        'isAnswered',
        'isStarted',
        'isInbound',
        'isOutbound',
    ];

    public static function handleEventUpdate(Request $request)
    {
        $call = static::firstOrCreate(['conversation_uuid' => $request->conversation_uuid], [
            'from' => $request->from,
            'to' => $request->to,
        ]);

        if ($request->has('status')) {
            switch ($request->status) {
                case 'started':
                    $call->update(['started' => Carbon::now()]);
                    break;
                case 'answered':
                    $call->update(['answered' => Carbon::now()]);
                    break;
                case 'completed':
                    $call->update(['completed' => Carbon::now(), 'price' => $request->price]);
                    break;
                default:
                    break;
            }
        }
    }

    public function getDirectionAttribute()
    {
        $lang = Config::get('ncco.lang.call.direction');
        return $this->isInbound ? $lang['inbound'] : $lang['outbound'];
    }

    public function getStatusAttribute()
    {
        $lang = Config::get('ncco.lang.call.status');

        if($this->isCompleted) {
            return $lang['completed'];
        }

        if($this->isAnswered) {
            return $lang['answered'];
        }

        if($this->isStarted) {
            return $lang['started'];
        }

        return 'Unknown Status';
    }

    public function getIsCompletedAttribute()
    {
        return $this->completed !== null;
    }

    public function getIsAnsweredAttribute()
    {
        return $this->answered !== null;
    }

    public function getIsStartedAttribute()
    {
        return $this->started !== null;
    }

    public function getIsInboundAttribute()
    {
        return $this->to === Config::get('ncco.number');
    }

    public function getIsOutboundAttribute()
    {
        return !$this->isInbound;
    }
}