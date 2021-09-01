<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use \App\Models\Participants;

class ValidUserForEvent implements Rule
{
    protected $request_inputs = array();

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
        $result = False;

        if(!empty($this->request_inputs['event_id']) && !empty($this->request_inputs['email']))
        {
            // check if email id is already there for selected event
            $already_participated = Participants::where('event_id', $this->request_inputs['event_id'])
                          ->where('email', $this->request_inputs['email'])
                          ->first();
            
            $result = !empty($already_participated) ? False : True;
        }
        
        return $result; 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You already participated in this event.';
    }
}
