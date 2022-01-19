<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedFloat('min_range');
            $table->unsignedFloat('max_range');
            $table->unsignedFloat('rate');
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unique(['asset_id', 'min_range', 'max_range', 'rate']);

            $table->timestamp('rate_creation_date', 0)->nullable();
            $table->timestamp('rate_update_date', 0)->nullable();
            $table->softDeletes('rate_deleted_at', 0)->nullable();

            $table->foreign('asset_id')->references('id')->on('asset_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
