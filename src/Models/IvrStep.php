<?php


namespace TaylorNetwork\LaravelNexmo\Models;

use Closure;
use Illuminate\Http\Request;

class IvrStep extends Model
{
    protected $fillable = [
        'action',
        'options',
        'order',
    ];

    protected $appends = [
        'prettyOptions',
    ];

    public function ivr()
    {
        return $this->belongsTo(Ivr::class);
    }

    public function getOptionsAttribute()
    {
        return json_decode($this->attributes['options'], true);
    }

    public function setOptionsAttribute($value)
    {
        switch(gettype($value)) {
            case 'string':
                try {
                    json_decode($value);
                } catch (\Exception $exception) {
                    throw new \Exception('Invalid JSON supplied to IvrStep->setOptionsAttribute');
                }
                break;

            case 'array':
            case 'object':
                $value = json_encode($value);
                break;

            default:
                throw new \Exception('IvrStep->setOptionsAttribute expected string|array|object and got ' . gettype($value));
        }

        dump($value);

        $this->attributes['options'] = $value;
    }

    public function getJsonOptionsAttribute()
    {
        return $this->attributes['options'];
    }

    public function getPrettyOptionsAttribute()
    {
        return json_encode($this->options, JSON_PRETTY_PRINT);
    }

    public function bootOrder()
    {
        static::saving(function (IvrStep $step) {
            if(!isset($this->attributes['order']) || !$this->attributes['order']) {
                $this->attributes['order'] = static::where('ivr_id', $step->attributes['ivr_id'])->get()->count();
            }
        });
    }

    public function handleRequestCallback(Request $request, Closure $closure)
    {
        $closure($this);
    }
}
