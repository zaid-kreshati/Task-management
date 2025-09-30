<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->decimal('discount', 5, 2)->nullable()->default(0); // Example: 25.50% discount

            $table->rememberToken(); // adds 'remember_token' column
            $table->timestamps();    // adds 'created_at' and 'updated_at' columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
