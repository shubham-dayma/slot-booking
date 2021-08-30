<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\CarbonPeriod;
use App\Models\Events;
use App\Models\EventTimings;

class EventSeeder extends Seeder
{
    /**
     * Store dummy event in DB table
     * @author : Shubham Dayma
     * @param  : Void
     * @return : Void
    */
    public function run()
    {
        $events[] = array(
                            'name'=>'PHP Seminars',
                            'total_seats'=>'100',
                            'available_seats'=>'100',
                            'event_start_date'=>'2021-08-31 08:00:00',
                            'event_end_date'=>'2021-09-03 20:00:00',
                            'min_minutes_before_start'=>'5'
                        );

        $events[] = array(
                            'name'=>'React Conferences',
                            'total_seats'=>'100',
                            'available_seats'=>'100',
                            'event_start_date'=>'2021-08-31 08:00:00',
                            'event_end_date'=>'2021-09-03 20:00:00',
                            'min_minutes_before_start'=>'0'
                        );

        $events[] = array(
                            'name'=>'Sports Trade Show',
                            'total_seats'=>'100',
                            'available_seats'=>'100',
                            'event_start_date'=>'2021-09-06 08:00:00',
                            'event_end_date'=>'2021-09-10 20:00:00',
                            'min_minutes_before_start'=>'10'
                        );

        foreach($events as $event)
        {
            // add event in db
            $event_id = Events::create($event)->id;
            
            $event_timings = [];

            // get dates comes between event's start and end variable
            $period = CarbonPeriod::create($event['event_start_date'], $event['event_end_date']);

            // add day wise timings in event_timings table
            foreach ($period as $date) 
            {
                $temp_array['event_id'] = $event_id;
                $temp_array['date'] = $date->format('Y-m-d');
                $temp_array['start_time'] = '08:00:00';
                $temp_array['end_time'] = '20:00:00';
                $temp_array['break_start_time'] = '13:00:00';
                $temp_array['break_end_time'] = '14:00:00';
                
                $event_timings[] = $temp_array;
            }

            EventTimings::insert($event_timings);
        }
    }
}
