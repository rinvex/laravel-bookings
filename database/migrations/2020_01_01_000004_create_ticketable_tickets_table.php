<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketableTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.bookings.tables.ticketable_tickets'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->morphs('ticketable');
            $table->string('slug');
            $table->json('name');
            $table->json('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('price')->default('0.00');
            $table->string('currency', 3)->nullable();
            $table->integer('quantity')->nullable()->default(-1);
            $table->mediumInteger('sort_order')->unsigned()->default(0);
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
        Schema::dropIfExists(config('rinvex.bookings.tables.ticketable_tickets'));
    }
}
