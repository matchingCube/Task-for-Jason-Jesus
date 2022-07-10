<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_schedule', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('year_num');
            $table->tinyInteger('week_num');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('driver_id');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('tcheck')->nullable();
            $table->string('spare_unit')->nullable();
            $table->string('fleet_net');
            $table->string('sat_start_time')->default('OFF');
            $table->string('sat_tractor_id')->nullable();
            $table->string('sun_start_time')->default('OFF');
            $table->string('sun_tractor_id')->nullable();
            $table->string('mon_start_time')->default('OFF');
            $table->string('mon_tractor_id')->nullable();
            $table->string('tue_start_time')->default('OFF');
            $table->string('tue_tractor_id')->nullable();
            $table->string('wed_start_time')->default('OFF');
            $table->string('wed_tractor_id')->nullable();
            $table->string('thu_start_time')->default('OFF');
            $table->string('thu_tractor_id')->nullable();
            $table->string('fri_start_time')->default('OFF');
            $table->string('fri_tractor_id')->nullable();
            $table->tinyInteger('sent_sms')->default(0);
            $table->tinyInteger('response')->default(0);
            $table->integer('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekly_schedule');
    }
}
