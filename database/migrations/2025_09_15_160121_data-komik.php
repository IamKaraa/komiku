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
        Schema::create('genres', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('comic', function(Blueprint $table){
            $table->id();
            $table->foreignid('user_id')->constrained('users')->onDelete('set null');;
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->string('thumbnail_path');
            $table->string('status');
            $table->foreignId('approved_by')->constrained('users')->onDelete('set null');
            $table->timestamp('apptoved_at');
            $table->timestamps();
        });

        Schema::create('comic_genre', function(Blueprint $table){
            $table->foreignId('comic_id')->constrained('comic')->onDelete('set Null');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('chapters', function(Blueprint $table){
            $table->id();
            $table->foreignId('comic_id')->constrained('comic')->onDelete('set null');
            $table->string('title');
            $table->integer('order_no');
            $table->timestamps();
        });

        Schema::create('chapter_images', function(Blueprint $table){
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('set null');
            $table->string('image_path');
            $table->integer('order_no');
            $table->timestamps();
        });

        Schema::create('rating', function(Blueprint $table){
            $table->id();
            $table->foreignId('comic_id')->constrained('comic')->onDelete('set null');
            $table->foreignid('user_id')->constrained('users')->onDelete('set null');
            $table->integer('rating');
            $table->timestamps();
        });

        Schema::create('comments', function(Blueprint $table){
            $table->id();
            $table->foreignId('comic_id')->constrained('comic')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
        Schema::dropIfExists('comic');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('chapters_images');
        Schema::dropIfExists('comic_genre');
        Schema::dropIfExists('rating');
        Schema::dropIfExists('comments');
    }
};
