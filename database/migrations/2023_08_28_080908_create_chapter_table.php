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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('storie_id'); 
            $table->unsignedInteger('order'); 
            $table->string('slug', 191);  
            $table->string('title', 255); 
            $table->text('content')->nullable();  
            $table->unsignedTinyInteger('status')->default(0); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
