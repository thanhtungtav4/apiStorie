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
        Schema::create('storie_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stories_id')->unsigned();
            $table->unsignedBigInteger('tags_id')->unsigned();
            $table->unique(['stories_id', 'tags_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storie_tag');
    }
};
