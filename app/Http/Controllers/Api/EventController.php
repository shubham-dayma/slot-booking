<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use \App\Models\Events;

use Illuminate\Http\Request;

class EventController extends Controller
{
	/**
     * @author : Shubham Dayma
     * @param  : Request object
     * @return list of future events
    */
	public function index(Request $request)
	{
		try
		{
			// get list of events greater then today and wrap it under data variable
			// We are using event_timings table to store event's timings for respective day. We can also add lunch hours for every day
			$data['events'] = Events::with(['timings'])
						->where('event_start_date', '>=', date('Y-m-d'))
						->get();
			
			// respond with json data 
			return $this->sendResponse('Participant added successfully.', 200, $data);
		}
		catch (Exception $ex) 
		{
			return $this->sendError('Bad Request.', 400);
        }
	}
}