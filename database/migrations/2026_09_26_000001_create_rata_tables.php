<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->string('grade');
            $table->string('code', 12)->unique();
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->string('topic');
            $table->string('grade');
            $table->text('notes')->nullable();
            $table->string('status')->default('draft'); // draft | published
            $table->string('source')->default('ai');    // ai | fixture
            $table->json('level_labels')->nullable();   // [1 => "Mengenali pecahan", ...]
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('level');
            $table->unsignedTinyInteger('order');
            $table->text('stem');
            $table->json('options'); // [{key, text, misconception|null}]
            $table->string('answer_key', 1);
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->string('student_name', 40);
            $table->unsignedTinyInteger('level')->nullable();
            $table->json('misconceptions')->nullable(); // [label => jumlah]
            $table->text('feedback')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('chosen_key', 1);
            $table->boolean('is_correct');
            $table->timestamps();
            $table->unique(['attempt_id', 'question_id']);
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('level');
            $table->json('content'); // {title, concept_md, examples_md, exercises[], teacher_tips_md}
            $table->string('source')->default('ai');
            $table->timestamps();
            $table->unique(['assessment_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('attempts');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('classrooms');
    }
};
