<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_category', function (Blueprint $table) {
            $table->id();
            $table->enum('title', ['Crypto', 'E-wallets', 'Gift Cards', 'Pay bills'])->unique();
            $table->string('code')->nullable();
            $table->string('image')->nullable();

            $table->timestamp('asset_category_creation_date', 0)->nullable();
            $table->timestamp('asset_category_update_date', 0)->nullable();
            $table->softDeletes('asset_category_deleted_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('biz_category');
    }
}
