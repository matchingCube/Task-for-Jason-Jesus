<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->enum('recurring', ['Yes', 'No'])->default('No');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('interval')->default(0);
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in progress', 'completed', 'cancelled'])->default('pending');
            $table->integer('user_id')->nullable();
            $table->integer('owner_id')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
