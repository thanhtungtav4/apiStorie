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
    /**
     * Create a new job instance.
     */
    public function __construct($crawl_stories_id, $stories_id)
    {
        $this->crawl_stories_id = $crawl_stories_id;
        $this->stories_id = $stories_id;
    }

    public function handle()
    {
        $crawl_stories_id = $this->crawl_stories_id;
        $stories_id = $this->stories_id;
        $savedIds = [];
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get('http://api.noveltyt.net/api/v2/chapters/numbers?end=5000&start=1&story_id=' . $crawl_stories_id);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    $mCrawlChapter = new CrawlChapters();
                    foreach ($data['data'] as $value) {
                        $dataStoriesSave = [
                            'chaper_number' => $value['number'],
                            'name' => $value['title'],
                            'crawl_stories_id' => $crawl_stories_id,
                            'stories_save_id'=> $stories_id,
                            'status' => 2,
                        ];
                        $savedId = $mCrawlChapter->store($dataStoriesSave);
                        $savedIds[] = $savedId;
                    }
                }
            } else {
                $errorMessage = $response->status() . ': ' . $response->body();
                // Log or handle the error message as needed
            }
        } catch (\Exception $e) {
            // Log or handle the exception as needed
        }

        // Optionally, return any results or information you need.
        // For example, you can return $savedIds or any other data.

        // Note: The job will automatically be dispatched to the queue when pushed.
    }
}
