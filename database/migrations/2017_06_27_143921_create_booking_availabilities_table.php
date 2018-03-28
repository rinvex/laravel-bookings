<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('rinvex.bookings.tables.availabilities'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->morphs('bookable');
            $table->string('is_available');
            $table->string('range');
            $table->string('range_from')->nullable();
            $table->string('range_to')->nullable();
            $table->smallInteger('priority')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('rinvex.bookings.tables.availabilities'));
    }
}
