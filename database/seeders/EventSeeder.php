<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
                            'slot_duration'=>'2',
                            'max_days_future'=>'10',
                            'min_minutes_before_start'=>'5'
                        );

        $events[] = array(
                            'name'=>'React Conferences',
                            'total_seats'=>'100',
                            'available_seats'=>'100',
                            'slot_duration'=>'2',
                            'max_days_future'=>'10',
                            'min_minutes_before_start'=>'5'
                        );

        $events[] = array(
                            'name'=>'Sports Trade Show',
                            'total_seats'=>'100',
                            'available_seats'=>'100',
                            'slot_duration'=>'2',
                            'max_days_future'=>'10',
                            'min_minutes_before_start'=>'10'
                        );

        foreach($events as $event)
        {
            // add event in db
            $event_id = Events::create($event)->id;
            
            $event_timings = [];

            // get dates comes between event's start and end variable
            $period = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

            // add day wise timings in event_timings table
            foreach ($period as $day) 
            {
                $start_time = "08:00:00";
                
                $end_time = "20:00:00";
                
                $break_start_time = "13:00:00";
                
                $break_end_time = "14:00:00";
                
                if(in_array($day, ['sunday', 'saturday']))
                {
                    $start_time = "00:00:00";
                    $end_time   = "00:00:00";
                    $break_start_time = "00:00:00";
                    $break_end_time   = "00:00:00";
                }
                
                $temp_array['event_id'] = $event_id;
                $temp_array['day'] = $day;
                $temp_array['start_time'] = $start_time;
                $temp_array['end_time'] = $end_time;
                $temp_array['break_start_time'] = $break_start_time;
                $temp_array['break_end_time'] = $break_end_time;
                
                $event_timings[] = $temp_array;
            }

            EventTimings::insert($event_timings);
        }
    }
}
