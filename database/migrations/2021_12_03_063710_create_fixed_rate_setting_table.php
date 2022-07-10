<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedRateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_rate_setting', function (Blueprint $table) {
            $table->id();
            $table->float('from_miles', 8, 4);
            $table->float('to_miles', 8, 4);
            $table->float('fixed_rate', 8, 4);
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_rate_setting');
    }
}
