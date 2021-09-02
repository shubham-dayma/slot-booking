<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use \App\Models\Events;

use Carbon\Carbon;

use Carbon\CarbonPeriod;

class EventAvailableForSelectedSlot implements Rule
{
    protected $request_inputs = array();

    protected $error_message = "";

    /**
     * Create a new rule instance.
     *
     * @return void
    */
    public function __construct($request_inputs = array())
    {
        if(!empty($request_inputs))
        {
            $this->request_inputs = $request_inputs;
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // check for all required fields
        if(
            !empty($this->request_inputs['slot_start_time']) 
            && !empty($this->request_inputs['slot_end_time'])
            && !empty($this->request_inputs['event_id'])
            && !empty($this->request_inputs['slot_date'])
        )
        {
            // validate request start and end time
            if(
                $this->request_inputs['slot_start_time'] >= $this->request_inputs['slot_end_time'] 
                || $this->request_inputs['slot_start_time'] == $this->request_inputs['slot_end_time']
            )
            {
                $this->error_message = 'Invalid slot selected.';
            }

            // check if selected slot is in future date
            if(empty($this->error_message))
            {
                $current_date_obj = Carbon::now();
                
                $current_date = $current_date_obj->format('Y-m-d');
                
                if($this->request_inputs['slot_date'] < $current_date)
                {
                    $this->error_message = 'Please select future date to processed.';
                }
            }

            // check if event id is valid and selected event is available for selected day
            if(empty($this->error_message))
            {
                // create array for event detail call
                $params['event_id']  = $value;

                $params['slot_day'] = strtolower(Carbon::parse($this->request_inputs['slot_date'])->format('l'));
                
                // get event details
                $event_details = Events::getDetails($params);
                
                if(empty($event_details))
                {
                    $this->error_message = 'Event is not available for selected date. Please change the date and try again.';
                }
            }

            // check if event is full
            if(empty($this->error_message) && $event_details->available_seats == 0)
            {
                $this->error_message = 'No seats available for this event. This event is full.';   
            }
            
            // check duration for selected time slots
            if(empty($this->error_message))
            {
                $start_time = Carbon::parse($this->request_inputs['slot_start_time']);
                
                $end_time = Carbon::parse($this->request_inputs['slot_end_time']);

                $totalDuration = $end_time->diffInMinutes($start_time);
                
                if($totalDuration != $event_details->slot_duration)
                {
                    $this->error_message = 'Duration of selected slot is invalid.';
                }
            }

            // check selected is for future and min left time for slot start is covered
            if(empty($this->error_message) && $this->request_inputs['slot_date'] == $current_date)
            {
                $current_time = $current_date_obj->format('H:i:s');
                   
                if(!empty($event_details->min_minutes_before_start))
                {
                    $current_time = $current_date_obj->addMinutes($event_details->min_minutes_before_start)->format('H:i:s');
                }
                
                if($this->request_inputs['slot_start_time'] <= $current_time)
                {
                    $this->error_message = 'Please select a valid slot.';
                }
            }

            // check x days in future case
            if(empty($this->error_message) && !empty($event_details->max_days_future))
            {
                $till_date = $current_date_obj->addDays($event_details->max_days_future)->format('Y-m-d');
                
                if($this->request_inputs['slot_date'] >= $till_date)
                {
                    $this->error_message = 'Please select date smaller then '.$till_date;
                }
            }

            // check if slot is valid
            if(empty($this->error_message))
            {
                // for create use 24 hours format later change format 
                $period = new CarbonPeriod($event_details->start_time, $event_details->slot_duration.' minutes', $event_details->end_time); 
                
                $slots = [];
                
                foreach($period as $item)
                {
                    $slot = $item->format("H:i:s");
                    
                    // save slots which not comes in break hours
                    if($event_details->break_start_time > $slot  
                        || $event_details->break_end_time <= $slot 
                    )
                    {
                        $slots[] = $slot; 
                    }
                }

                if(!in_array($this->request_inputs['slot_start_time'], $slots))
                {
                    $this->error_message = 'Invalid slot selected';
                }
            }
            
        }
        else
        {
            $this->error_message = 'Missing required fields.';
        }
        
        return empty($this->error_message) ? True : False;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
