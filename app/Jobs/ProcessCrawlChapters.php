<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CrawlChapters;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;

class ProcessCrawlChapters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $crawl_stories_id;
    protected $stories_id;
    protected $chapter_count;
    public function __construct($crawl_stories_id, $stories_id, $chapter_count)
    {
        $this->crawl_stories_id = $crawl_stories_id;
        $this->stories_id = $stories_id;
        $this->chapter_count = $chapter_count;
    }

    public function handle()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get("https://api.noveltyt.xyz/api/v2/chapters/numbers?end=" . $this->chapter_count . "&start=1&story_id=" . $this->crawl_stories_id);

            if (!$response->successful()) {
                $errorMessage = $response->status() . ': ' . $response->body();
                \Log::info('An error occurred while processing');
                return;
            }

            $data = $response->json();
           // var_dump($data);
            if (!empty($data['data'])) {
                foreach ($data['data'] as $value) {
                    $this->createCrawlChapter($value);
                }
            }
        } catch (\Exception $e) {
            \Log::info('An error occurred while processing');
        }
    }

    private function createCrawlChapter($value)
    {
        $CrawlChapters = new CrawlChapters();
        $dataStoriesSave = [
            'chaper_number' => $value['number'],
            'name' => $value['title'],
            'crawl_stories_id' => $this->crawl_stories_id,
            'stories_save_id' => $this->stories_id,
            'status' => 2,
        ];
        $CrawlChapters->store($dataStoriesSave);
    }
}