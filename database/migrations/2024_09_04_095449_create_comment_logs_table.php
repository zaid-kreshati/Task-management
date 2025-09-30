<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentLogsTable extends Migration
{
    public function up()
    {
        Schema::create('comment_logs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->text('user_name')->nullable(); // Name of the user
            $table->string('action'); // 'insert' or 'delete'
            $table->text('comment'); // The comment affected
            $table->string('type')->nullable(); // Type of the commentable (e.g., 'post', 'article')
            $table->unsignedBigInteger('comment_id')->nullable(); // ID of the affected comment
            $table->unsignedBigInteger('user_id')->nullable(); // ID of the user who made the action
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_logs'); // Drop the comment_logs table
    }
}
