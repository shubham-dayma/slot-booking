<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $guarded = [];

    /**
     * Creating relation between event and event_timings tables
     * @author : Shubham Dayma
     * @param  : Void
     * @return : event day timings include breaks
    */
    public function timings()
    {
        return $this->hasMany(EventTimings::class, 'event_id');
    }

    /**
     * Event details to validate select slot
     * @author : Shubham Dayma
     * @param  : params array with event id and selected slot date
     * @return : event details including timings based on conditions
    */
    public static function getDetails($params)
    {
        $self_obj = self::select(
                                'events.available_seats',
                                'events.min_minutes_before_start',
                                'events.max_days_future',
                                'events.slot_duration',
                                'event_timings.*'
                            );
        
        $self_obj->join('event_timings', function($q) use ($params)
        {
            $q->on('event_timings.event_id', 'events.id');
            $q->where('event_timings.day', $params['slot_day']);
            $q->where('event_timings.start_time', '!=', '00:00:00');
            $q->where('event_timings.end_time', '!=', '00:00:00');
        });

        $self_obj->where('events.id', $params['event_id']);
        
        return $self_obj->first();
    }
}
