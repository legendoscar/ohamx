<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('asset_id');
            $table->enum('txn_status', ['processing', 'declined', 'failed', 'success']);

            $table->timestamp('txn_creation_date', 0)->nullable();
            $table->timestamp('txn_update_date', 0)->nullable();
            $table->softDeletes('txn_deleted_at', 0)->nullable();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('transactions');
    }
}
