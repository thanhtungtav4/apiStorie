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
        Schema::create('crawl_stories', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name')->nullable()->default(null);
            $table->integer('chapter_count')->nullable();
            $table->string('stories_id')->nullable()->default(null);
            $table->string('stories_save_id')->nullable()->default(null);
            $table->enum('status', ['draft', 'crawl', 'crawl done'])->default('draft');
            $table->enum('status_chapter', ['crawl', 'crawl done'])->default('crawl');
            $table->timestamps();
        });
    }

    /**
     *
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawl_stories');
    }
};
