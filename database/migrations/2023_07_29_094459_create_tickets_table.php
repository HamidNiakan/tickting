<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
			$table->string('title');
			$table->foreignId('user_id')->constrained('users');
			$table->enum('priority',['low','high','medium'])->default('low');
			$table->enum('status',['closed','open'])->default('open');
			$table->boolean('is_read')->default(0);
			$table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
