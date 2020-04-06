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

    public function ivr()
    {
        return $this->belongsTo(Ivr::class);
    }

    public function getOptionsAttribute()
    {
        return $this->checkForReplaceValues(json_decode($this->attributes['options'], true));
    }

    // @todo this is not going to work as expected I'm SURE
    
    public function checkForReplaceValues(array $options)
    {
        foreach($options as $key => $value) {
            if(is_array($value)) {
                return $this->checkForReplaceValues($value);
            }

            if(preg_match_all('/{.*}/', $value, $matches) > 0) {
                dd($matches);
            }
        }

        return $options;
    }

    public function replaceValue()
    {

    }

    public function setOptionsAttribute(array $value)
    {
        $this->attributes['options'] = json_encode($value);
    }

    public function getJsonOptionsAttribute()
    {
        return $this->attributes['options'];
    }

    public function printJsonOptions()
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