<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDateToDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_timings', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->string('day', 100)->after('event_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_timings', function (Blueprint $table) {
            $table->dropColumn('daty');
            $table->date('date')->after('event_id');
        });
    }
}
