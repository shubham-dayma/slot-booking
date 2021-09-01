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
			// get event details
			$event_details = Events::sharedLock()->find($request->event_id);

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
			$event_details->available_seats = $event_details->available_seats - 1;

			$event_details->save();

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