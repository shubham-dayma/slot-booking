<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Participant\StoreRequest;

use \App\Models\Participants;

use \App\Models\Events;

use \DB;

class ParticipantController extends Controller
{
	/**
     * Add participants in DB
     * @author : Shubham Dayma
     * @param  : Validated request object, we have added simple request validation code at StoreRequest 
     * @return success/failur message
    */
	public function store(StoreRequest $request)
	{
		try
		{
			// validate request start and end time
			if($request->slot_start_time >= $request->slot_end_time || $request->slot_start_time == $request->slot_end_time)
			{
				return $this->sendError('Invalid slot selected.', 400);
			}

			// create array for event detail call
			$params['event_id']  = $request->event_id;

			$params['slot_date'] = $request->slot_date;
			
			// get event details
			$event_details = Events::getDetails($params);

			// if event_id is invalid or selected event is not available for selected date.
			if(empty($event_details))
			{
				return $this->sendError('Event is not available for selected date. Please change the date and try again', 400);	
			}

			// check if participants are full
			if($event_details[0]->available_seats == 0)
			{
				return $this->sendError('No seats available for this event. This event is full.', 400);
			}

			// store start time of event in temp. variable for validation
			$event_start_time = $event_details[0]->event_start_date;

			// check if min_minutes_before_start is set
			if(!empty($event_details[0]->min_minutes_before_start))
			{
				// temp. revise start time based on min_minutes_before_start
				$event_start_time = date('Y-m-d H:i:s', strtotime($event_start_time.' - '.$event_details[0]->min_minutes_before_start.' minute'));
			}

			// if current time is greater then temp. start time then request is invalid
			if($event_start_time < date('Y-m-d H:i:s'))
			{
				return $this->sendError('Booking window is closed.', 400);
			}

			// each of event timings, for now we assume that per day there will be only single entry in event_timings table, but incase there are mulitple enteries for same day and for same event then we can expand below block of code and same is the reason behind using foreach
			foreach($event_details as $event)
			{
				// validates selected slots timings, check if selected slot comes between start and end time and also validates weather selected slots comes in break hours
				if(
					$event->start_time > $request->slot_start_time
					|| $event->end_time < $request->slot_end_time
					|| ($event->break_start_time <= $request->slot_start_time && $event->break_end_time > $request->slot_start_time)
					|| ($event->break_start_time < $request->slot_end_time && $event->break_end_time >= $request->slot_end_time)
					|| ($event->break_start_time > $request->slot_start_time && $event->break_end_time < $request->slot_end_time)
				)
				{
					return $this->sendError('Invalid slot selected.', 400);	
				}
			}

			// check if email id is already there for selected event
			$already_participated = Participants::where('event_id', $request->event_id)
						  ->where('email', $request->email)
						  ->first();

			if(!empty($already_participated))
			{
				return $this->sendError('You already participated in this event.', 400);
			}

			// begin db transaction statement, if request is valid
			DB::beginTransaction();
			
			$participant = new Participants();
			
			$participant->event_id = $request->event_id;

			$participant->email = $request->email;
			
			$participant->first_name = $request->first_name;
			
			$participant->last_name = $request->last_name;

			$participant->slot_date = $request->slot_date;

			$participant->slot_start_time = $request->slot_start_time;
			
			$participant->slot_end_time = $request->slot_end_time;
			
			// add participant in DB
			$participant->save();
			
			// reduce available seats from event
			$event_update_array['available_seats'] = $event_details[0]->available_seats - 1;

			Events::where('id', $request->event_id)->update($event_update_array);

			// if all good, then commit the db trasnsation
			DB::commit();

			// respond success message
			return $this->sendResponse('Participant added successfully.', 200);
			
		}
		catch (Exception $ex) 
		{
			// incase of any exception occured, rollback the transation if it present and return "Bad Request" message
			DB::rollback();

			return $this->sendError('Bad Request.', 400);
        }
	}
}