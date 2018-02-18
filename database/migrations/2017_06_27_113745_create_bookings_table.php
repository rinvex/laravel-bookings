<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('rinvex.bookings.tables.bookings'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->morphs('bookable');
            $table->morphs('user');
            $table->string('currency', 3);
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('ends_at')->useCurrent();
            $table->decimal('price')->default('0.00');
            $table->{$this->jsonable()}('price_equation')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists(config('rinvex.bookings.tables.bookings'));
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
