<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_list', function (Blueprint $table) {
            $table->id();
            $table->string('asset_title');
            $table->unsignedBigInteger('asset_cat_id');
            $table->string('asset_code');
            $table->string('asset_image')->nullable();
            $table->text('asset_tc')->nullable();
            $table->boolean('is_available')->default(1);
            $table->boolean('is_new')->default(0);
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_recommended')->default(0);

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('asset_cat_id')->references('id')->on('asset_category');
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
