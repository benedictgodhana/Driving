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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique(); // Unique code for the course
            $table->string('course_name'); // Name of the course
            $table->unsignedBigInteger('user_id'); // Foreign key reference to the user who saved the course
            $table->unsignedInteger('number_lessons'); // Number of lessons in the course
            $table->decimal('amount', 10, 2); // Amount for the course (e.g., fee)
            $table->timestamps(); // Includes created_at and updated_at columns

            // Add foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
