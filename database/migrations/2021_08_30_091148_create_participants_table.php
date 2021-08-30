<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index()->comment('Primary key of events table');
            $table->string('email', 255)->comment('Email Id of participant');
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->date('slot_date');
            $table->time('slot_start_time');
            $table->time('slot_end_time');
            $table->dateTime('inserted_at')->useCurrent();

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
        Schema::dropIfExists('participants');
    }
}
