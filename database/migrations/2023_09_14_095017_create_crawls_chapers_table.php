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
        Schema::create('crawl_chapers', function (Blueprint $table) {
            $table->id();
            $table->integer('chaper_number');
            $table->string('name')->nullable()->default(null);
            $table->string('crawl_stories_id')->default(null);
            $table->string('stories_save_id')->nullable()->default(null);
            $table->enum('status', ['draft', 'crawl', 'crawl done'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawl_chapers');
    }
};
