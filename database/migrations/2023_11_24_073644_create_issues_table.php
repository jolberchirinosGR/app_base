<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('company', 150);
            $table->string('category', 150)->nullable();
            $table->string('bus', 100)->nullable();
            $table->string('line', 100)->nullable();
            $table->string('driver', 100)->nullable();
            $table->string('employee', 150)->nullable();
            $table->time('hour')->nullable();
            $table->string('action', 250)->nullable();
            $table->time('notice_time')->nullable();
            $table->string('change_bus', 100)->nullable();
            $table->time('solution_time')->nullable();
            $table->text('description')->nullable();
            $table->time('time')->nullable();

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
        Schema::dropIfExists('issues');
    }
};
