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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->dateTime('departure_time');
            $table->char('departure_tz');
            $table->bigInteger('departure_airport')->index();
            $table->dateTime('arrival_time');
            $table->char('arrival_tz');
            $table->bigInteger('arrival_airport')->index();
            $table->integer('available_seats');
            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('departure_airport')->references('id')->on('airports');
//            $table->foreign('arrival_airport')->references('id')->on('airports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
};
