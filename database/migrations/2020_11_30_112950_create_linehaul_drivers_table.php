<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinehaulDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linehaul_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('driver_id');
            $table->string('driver_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('license')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->float('price_per_mile', 8, 4)->default(0.0000);
            $table->tinyInteger('work_status')->default('1');
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
        Schema::dropIfExists('linehaul_drivers');
    }
}
