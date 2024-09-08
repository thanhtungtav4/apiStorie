<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Chapter;
use App\Models\CrawlChapters;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProcessSaveChapters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chapterOrder;
    protected $crawlId;
    protected $storiesId;
    protected $updateCrawlId;

    /**
     * Create a new job instance.
     */
    public function __construct($chapterOrder, $crawlId, $storiesId, $updateCrawlId)
    {
        $this->chapterOrder = $chapterOrder;
        $this->crawlId = $crawlId;
        $this->storiesId = $storiesId;
        $this->updateCrawlId = $updateCrawlId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $order = $this->chapterOrder;
        $crawlId = $this->crawlId;
        $storiesId = $this->storiesId;
        $updateCrawlId = $this->updateCrawlId;
        $savedId = null; // Initialize the savedId variable

        // Use try-catch to handle exceptions gracefully
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get("https://api.noveltyt.xyz/api/v2/chapters/detail?number=$order&story_id=$crawlId");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    $mChapter = new Chapter();
                    $dataSave = [
                        'title' => $data['data']['title'],
                        'slug' => Str::slug($data['data']['title']),
                        'content' => $data['data']['content'],
                        'order' => $data['data']['number'],
                        'storie_id' => $storiesId,
                        'status' => 2,
                    ];
                    // Save the chapter
                    $savedChapter = $mChapter->store($dataSave);
                    if ($savedChapter->id) {
                        $crawlChapter = new CrawlChapters;
                        $crawlChapter->updateStatus($updateCrawlId);
                    }
                    $savedId = $savedChapter->id;
                }
            } else {
                \Log::info('An error occurred while processing');
            }
        } catch (Exception $e) {
            \Log::error('An error occurred while processing: ' . $e->getMessage());
        }
    }
}
