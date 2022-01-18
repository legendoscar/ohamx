<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoCoinsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_coins_list', function (Blueprint $table) {
            $table->id();
            $table->string('coin_title');
            $table->string('coin_code');
            $table->string('coin_image')->nullable();
            $table->text('coin_tc')->nullable();
            $table->boolean('is_available')->default(1);
            $table->boolean('is_new')->default(0);
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_recommended')->default(0);

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
        Schema::dropIfExists('crypto_coins_list');
    }
}
