<?php


namespace TaylorNetwork\LaravelNexmo\Models;

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
        return json_decode($this->attributes['options'], true);
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
}