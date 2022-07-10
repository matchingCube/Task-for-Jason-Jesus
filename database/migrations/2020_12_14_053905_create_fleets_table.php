<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('tractor_id');
            $table->string('model')->nullable();
            $table->string('vin')->nullable();
            $table->year('year')->nullable();
            $table->string('license_plate')->nullable();
            $table->string('t_check')->nullable();
            $table->string('pre_pass')->nullable();
            $table->string('service_provider')->nullable();
            $table->string('qiv')->nullable();
            $table->date('bit')->nullable();
            $table->string('domicile')->nullable();
            $table->string('domicile_email')->nullable();
            $table->float('book_value', 20, 4)->default(0);
            $table->string('vedr')->nullable();
            $table->string('eld')->nullable();
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
        Schema::dropIfExists('fleets');
    }
}
