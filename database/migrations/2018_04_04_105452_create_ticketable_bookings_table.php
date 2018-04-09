<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketableBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.bookings.tables.ticketable_bookings'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->decimal('paid')->default('0.00');
            $table->string('currency', 3)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('rinvex.bookings.tables.ticketable_bookings'));
    }
}
