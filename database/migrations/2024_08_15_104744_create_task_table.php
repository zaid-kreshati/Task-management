<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TaskStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            //$table->json('task_description');
            $table->json('task_description'); // Store task descriptions as JSON

            // You can either use a string with default status or the enum type
            // $table->enum('status', [TaskStatus::TO_DO->value, TaskStatus::IN_PROGRESS->value, TaskStatus::DONE->value])
            //      ->default(TaskStatus::TO_DO->value);
            $table->string('status');
            $table->boolean('end_flag')->default(false);
            $table->dateTime('dead_line');
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
            $table->softDeletes(); // Adds `deleted_at` column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
};
