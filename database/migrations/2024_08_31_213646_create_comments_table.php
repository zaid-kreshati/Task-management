<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); // ID of the user who made the comment
            $table->morphs('commentable'); // Creates `commentable_id` and `commentable_type`
            $table->json('comment'); // The actual comment text
            $table->timestamps(); // Adds `created_at` and `updated_at`
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes

            $table->index(['commentable_type', 'commentable_id', 'user_id']); // Index for efficient querying
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Drop the soft delete column
            $table->dropIndex(['commentable_type', 'commentable_id', 'user_id']); // Drop the index
        });

        Schema::dropIfExists('comments'); // Drop the comments table
    }
}
