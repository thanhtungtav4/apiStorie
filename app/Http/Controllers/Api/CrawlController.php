<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrawlStories;
use App\Models\CrawlChapters;
use App\Models\Chapter;
use App\Models\Stories;
use App\Models\Genres;
use App\Models\Authors;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Jobs\ProcessCrawlChapters;
use App\Jobs\ProcessSaveChapters;
use Illuminate\Support\Str;

class CrawlController extends Controller
{
    public function getList()
    {
        $savedIds = [];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://api.noveltyt.xyz/api/v2/stories/list');

        if (!$response->successful()) {
            $errorMessage = $response->status() . ': ' . $response->body();
            dd($errorMessage);
        }

        $data = $response->json();
       
        if (!empty($data['data'])) {
            foreach ($data['data'] as $value) {
                $story = $this->createStory($value);
                $crawlStory = $this->createCrawlStory($value, $story->id);
                dispatch(new ProcessCrawlChapters($value['_id']['$oid'], $story->id, $value['chapter_count']));
            }
        }

        return $savedIds;
    }

    private function createStory($value)
    {
        $Stories = new Stories();
        $Genres = new Genres();
        $Authors = new Authors();

        if (!empty($value['genre'])) {
            $dataGenre = $Genres->getIdsOrCreate($value['genre']);
        }
        if(!empty($value['author'])) {
            $dataAuthor = $Authors->getIdsOrCreateAuthor($value['author']);
        }
        $dataStorie = [
            'title' => $value['title'],
            'slug' => Str::slug($value['title']),
            'author_id' => $dataAuthor ?? 0,
            'genres' => $dataGenre ?? '',
            'description' => '',
            'image_cover' => $value['cover'] ?? '',
            'image_feature' =>  '',
            'status' => '2',
        ];

        return $Stories->store($dataStorie);
    }

    private function createCrawlStory($value, $storyId)
    {
        $CrawlStories = new CrawlStories();
        $dataSave = [
            'type' => "noveltyt",
            'chapter_count' => $value['chapter_count'],
            'name' => $value['title'],
            'stories_id' => $value['_id']['$oid'],
            'stories_save_id' => $storyId,
            'status' => 2,
            'status_chapter' => 1,
        ];

        return $CrawlStories->store($dataSave);
    }

    // Updated saveCrawlChaperJob method
    public function saveCrawlChaperJob()
    {
        $CrawlChaper = new CrawlChapters();
        $DataChaper = $CrawlChaper->getAll();
        foreach ($DataChaper as $chaper) {
            // Dispatch jobs with individual parameters
            dispatch(new ProcessSaveChapters($chaper->chaper_number, $chaper->crawl_stories_id, $chaper->stories_save_id, $chaper->id));
        }
    }


}
