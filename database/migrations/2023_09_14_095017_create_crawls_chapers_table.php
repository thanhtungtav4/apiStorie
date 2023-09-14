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
            $table->id('chaper_number');
            $table->string('name')->nullable()->default(null);
            $table->unsignedBigInteger('crawl_stories_id');
            $table->enum('status', ['draft', 'is crawl', 'crawl done'])->default('draft');
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
