<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherSettlementAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_settlement_adjustments', function (Blueprint $table) {
            $table->id();
            $table->integer('year_num');
            $table->integer('week_num');
            $table->date('date');
            $table->char('type', 255);
            $table->char('description', 255);
            $table->float('amt', 8, 4);
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
        Schema::dropIfExists('other_settlement_adjustments');
    }
}
