<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddslotDurationAndXdaysFutureColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_start_date', 'event_end_date']);
            $table->integer('slot_duration')->default(0)->comment('Slot duration in minutes')->after('available_seats');
            $table->integer('max_days_future')->default(0)->after('available_seats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('event_end_date')->after('available_seats');
            $table->dateTime('event_start_date')->after('available_seats');
            $table->dropColumn(['max_days_future', 'slot_duration']);
        });
    }
}
