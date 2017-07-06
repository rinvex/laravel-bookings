<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.bookable.tables.booking_rates'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('bookable_id')->unsigned();
            $table->string('bookable_type');
            $table->tinyInteger('percentage');
            $table->char('operator', 1);
            $table->integer('amount')->unsigned();
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
        Schema::dropIfExists(config('rinvex.bookable.tables.booking_rates'));
    }
}
