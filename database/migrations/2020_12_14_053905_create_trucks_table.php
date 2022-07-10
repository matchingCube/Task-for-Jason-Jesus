<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tractors', function (Blueprint $table) {
            $table->id();
            $table->char('tractor_id', 255);
            $table->char('model', 255);
            $table->char('vin', 255);
            $table->year('year');
            $table->char('license_plate', 255);
            $table->float('last_bit_miles', 20, 4);
            $table->date('bit');
            $table->char('oil_changes', 255);
            $table->float('insurance_book_value', 20, 4);
            $table->char('smart_witness_serial', 255);
            $table->char('omnitracs_device_id', 255);
            $table->char('pre_pass', 255);
            $table->char('t_check', 255);
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
        Schema::dropIfExists('tractors');
    }
}
