<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); 
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->boolean('isAdmin')->default(0);
            $table->boolean('isActive')->default(1);
            $table->string('dob')->nullable();
            $table->string('location')->nullable();
            $table->string('designation')->nullable();
            $table->string('gender')->nullable();
            $table->string('profile_image')->nullable();
            $table->date('email_verified_at')->nullable();
            $table->date('phone_verified_at')->nullable();
            $table->rememberToken();

            $table->timestamp('user_creation_date', 0)->nullable();
            $table->timestamp('user_update_date', 0)->nullable();
            $table->softDeletes('user_deleted_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
