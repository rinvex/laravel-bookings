<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookableBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('rinvex.bookings.tables.bookable_bookings'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->morphs('bookable');
            $table->morphs('customer');
            $table->string('currency', 3);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->string('timezone')->nullable();
            $table->decimal('price')->default('0.00');
            $table->{$this->jsonable()}('price_equation')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_attended')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('rinvex.bookings.tables.bookable_bookings'));
    }

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable(): string
    {
        return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
               && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
            ? 'json' : 'text';
    }
}
