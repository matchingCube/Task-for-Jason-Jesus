<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinehaulTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linehaul_trips', function (Blueprint $table) {
            $table->id();
            $table->integer('year_num');
            $table->integer('week_num');
            $table->date('date');
            $table->char('vehicle', 255);
            $table->char('trip_id', 255);
            $table->char('leg_org', 255);
            $table->char('leg_dest', 255);
            $table->char('zip_postal', 255);
            $table->float('miles_qty', 8, 4);
            $table->float('vmr_rate', 8, 4);
            $table->float('mileage_plus', 8, 4);
            $table->float('premiums', 8, 4);
            $table->float('fuel', 8, 4);
            $table->float('total_rate', 8, 4);
            $table->float('amt_1', 8, 4);
            $table->float('pkgs', 8, 4);
            $table->float('amt_2', 8, 4);
            $table->float('d_and_h', 8, 4);
            $table->float('tolls', 8, 4);
            $table->float('flat_rate', 8, 4);
            $table->float('daily_gross_amt', 8, 4);
            $table->char('driver_1', 255);
            $table->char('driver_2', 255);
            $table->integer('company_id');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linehaul_trips');
    }
}
