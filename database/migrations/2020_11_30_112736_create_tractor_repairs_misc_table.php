<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTractorRepairsMiscTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tractor_repairs_misc', function (Blueprint $table) {
            $table->id();
            $table->integer('year_num');
            $table->integer('week_num');
            $table->date('date');
            $table->char('ticket_check_id', 255);
            $table->char('vehicle', 255);
            $table->char('truck_stop', 255);
            $table->char('city', 255);
            $table->char('state', 255);
            $table->char('description', 255);
            $table->float('pur_amt', 8, 4);
            $table->float('repair_misc_amt', 8, 4);
            $table->float('auth_chgbk_arrears', 8, 4);
            $table->float('auth_chgbk_refund', 8, 4);
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
        Schema::dropIfExists('tractor_repairs_misc');
    }
}
