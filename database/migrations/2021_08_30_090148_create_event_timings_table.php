<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        echo "NOTE: We are using this table to store event's timings for respective day. We can also add lunch hours for every day.";

        Schema::create('event_timings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index()->comment('Primary key of events table');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start_time')->nullable();
            $table->time('break_end_time')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_timings');
    }
}
