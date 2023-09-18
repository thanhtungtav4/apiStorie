<?php

namespace App\Jobs;

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
    protected $chaper_order;
    protected $crawl_id;
    protected $stories_id;
    protected $update_crawl_id;
    /**
     * Create a new job instance.
     */
    public function __construct($chaper_order, $crawl_id, $stories_id, $update_crawl_id)
    {
        $this->chaper_order = $chaper_order;
        $this->crawl_id = $crawl_id;
        $this->stories_id = $stories_id;
        $this->update_crawl_id = $update_crawl_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = $this->chaper_order;
        $crawl_id = $this->crawl_id;
        $stories_id = $this->stories_id;
        $update_crawl_id = $this->update_crawl_id;
        $savedId = null; // Initialize the savedId variable
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('http://api.noveltyt.net/api/v2/chapters/detail?number='. $order .'&story_id=' . $crawl_id);

        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['data'])) {
                $mChapter = new Chapter();
                $dataSave = [
                    'title' =>  $data['data']['title'],
                    'slug' =>   Str::slug($data['data']['title']),
                    'content' => $data['data']['content'],
                    'order' => $data['data']['number'],
                    'storie_id' => $stories_id,
                    'status' => 2,
                ];
                // Save the chapter
                $savedChapter = $mChapter->store($dataSave);
                if($savedChapter->id){
                    $CrawlChapter = new CrawlChapters;
                    $CrawlChapter->updateStatus($update_crawl_id);
                }
                $savedId = $savedChapter->id;
            }
        } else {
            $errorMessage = $response->status() . ': ' . $response->body();
            dd($errorMessage);
        }
        \Log::info('Data save chaper processed: ' . json_encode($savedId));
    }
}
