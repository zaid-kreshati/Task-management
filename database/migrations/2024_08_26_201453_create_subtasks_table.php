<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TaskStatus;


class CreateSubtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Name of the subtask
            // $table->enum('status', [
            //     TaskStatus::TO_DO->value,
            //     TaskStatus::IN_PROGRESS->value,
            //     TaskStatus::DONE->value
            // ])->default(TaskStatus::TO_DO->value);
            $table->string('status')->default(TaskStatus::TO_DO);

            $table->unsignedBigInteger('task_id'); // Foreign key for the related task
            $table->timestamps();
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes


            // Foreign key constraint
            $table->foreign('task_id')
                  ->references('id')
                  ->on('task')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
        });
        Schema::dropIfExists('subtasks');
    }
}
