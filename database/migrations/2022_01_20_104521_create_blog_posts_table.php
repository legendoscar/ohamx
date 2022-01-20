<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->string('post_title')->unique();
            $table->text('post_body');
            $table->string('post_slug')->nullable();
            $table->string('post_image')->nullable();
            $table->boolean('isActve')->default(1);
            $table->boolean('isPublished')->default(1);
            $table->date('date_drafted')->nullable();
            $table->date('date_published')->nullable();
            // $table->unsignedBigInteger('cat_id')->nullable();
            // $table->unsignedBigInteger('tag_id')->nullable();
            $table->timestamp('blog_post_creation_date', 0)->nullable();
            $table->timestamp('blog_post_update_date', 0)->nullable();
            $table->softDeletes('blog_post_deleted_at', 0)->nullable();

            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
