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
            $table->unsignedBigInteger('asset_cat_id');
            $table->string('asset_title');
            $table->string('asset_symbol');
            $table->string('asset_slug')->nullable();
            $table->string('asset_image')->nullable();
            $table->text('asset_tc')->nullable();
            $table->boolean('is_available')->default(1);
            $table->boolean('is_new')->default(0);
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_recommended')->default(0);

            $table->timestamp('asset_list_creation_date', 0)->nullable();
            $table->timestamp('asset_list_update_date', 0)->nullable();
            $table->softDeletes('asset_list_deleted_at', 0)->nullable();


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
        Schema::dropIfExists('asset_list');
    }
}
