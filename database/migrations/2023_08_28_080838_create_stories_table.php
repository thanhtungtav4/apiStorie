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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();  
            $table->string('slug', 191)->unique();  
            $table->string('title', 255);  
            $table->text('description')->nullable(); 
            $table->string('image_cover', 255)->nullable(); 
            $table->string('image_feature', 255)->nullable();  
            $table->unsignedTinyInteger('status')->default(0);  
            $table->unsignedSmallInteger('author_id')->nullable();
            $table->softDeletes();  
            $table->timestamps();  
            // Index for fast lookup by status
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
