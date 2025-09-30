<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationLogsTable extends Migration
{
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->string('operation'); // insert or delete
            $table->json('data')->nullable(); // log the data as JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operation_logs');
    }
}
