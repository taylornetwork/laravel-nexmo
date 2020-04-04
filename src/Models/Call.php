<?php


namespace TaylorNetwork\LaravelNexmo\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;

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
}