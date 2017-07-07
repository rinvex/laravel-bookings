<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.bookable.tables.booking_availability'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('bookable_id')->unsigned();
            $table->string('bookable_type');
            $table->char('day', 3);
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->decimal('price')->default('0.00');
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
        Schema::dropIfExists(config('rinvex.bookable.tables.booking_availability'));
    }
}
