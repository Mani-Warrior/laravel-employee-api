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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_no', 25)->unique();
            $table->string('email')->unique();
            $table->string('designation');
            $table->date('joining_date');
            $table->decimal('salary', 10, 2)->default(0);
            $table->integer('age');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
