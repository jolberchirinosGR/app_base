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
        Schema::create('lost_objects', function (Blueprint $table) {
            $table->id();

            $table->string('code', 150);
            $table->date('date');
            $table->string('register', 150);
            $table->string('bus', 100)->nullable();
            $table->string('line', 100)->nullable();
            $table->string('driver', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('delivered_by', 150)->nullable();
            $table->string('reception_by', 150)->nullable();
            $table->date('reception_date')->nullable();
            $table->string('destination', 250)->nullable();
            $table->date('destination_date')->nullable();

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
        Schema::dropIfExists('lost_objects');
    }
};
