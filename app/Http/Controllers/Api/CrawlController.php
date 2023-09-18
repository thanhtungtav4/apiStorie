<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrawlStories;
use App\Models\CrawlChapters;
use App\Models\Chapter;
use App\Models\Stories;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Jobs\ProcessCrawlChapters;
use App\Jobs\ProcessSaveChapters;
use Illuminate\Support\Str;

class CrawlController extends Controller
{
    public function getListInterface()
    {
        $savedIds = []; // Initialize an array to store saved IDs
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('http://api.noveltyt.net/api/v2/stories/list');

        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['data'])) {
                $mStories = new Stories();
                $mCrawlStories = new CrawlStories();
                foreach ($data['data'] as $value) {
                    $dataStorie = [
                        'title' => $value['title'],
                        'slug' =>  Str::slug($value['title']),
                        'author' => $value['author'],
                        'genres' => '',
                        'description' => '',
                        'image_cover' => '',
                        'image_future' => '',
                        'status' => '1',
                    ];
                    $StorieId = $mStories->store($dataStorie);
                    $dataSave = [
                        'type' => "noveltyt",
                        'chapter_count' => $value['chapter_count'],
                        'name' => $value['title'],
                        'stories_id' => $value['_id']['$oid'],
                        'stories_save_id' => $StorieId->id,
                        'status' => 2,
                        'status_chapter' => 1,
                    ];
                    $savedId = $mCrawlStories->store($dataSave);
                    $savedIds[] = $savedId; // Add the saved ID to the array
                    if ($savedId) {
                        dispatch(new ProcessCrawlChapters($value['_id']['$oid'], $StorieId->id));
                    }
                }
            }
        } else {
            $errorMessage = $response->status() . ': ' . $response->body();
            dd($errorMessage);
        }
        return $savedIds; // Return the array of saved IDs
    }

    public function saveCrawlChaperJob(){
        $CrawlChaper = new CrawlChapters();
        $DataChaper = $CrawlChaper->getAll();
        foreach ($DataChaper as $key => $chaper) {
            dispatch(new ProcessSaveChapters($chaper->chaper_number, $chaper->crawl_stories_id, $chaper->stories_save_id, $chaper->id));
        }
    }

    // public function saveChaper($number, $crawstory, $storie){
    //     $savedId = null; // Initialize the savedId variable
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //     ])->get('http://api.noveltyt.net/api/v2/chapters/detail?number='. $number .'&story_id=' . $crawstory);

    //     if ($response->successful()) {
    //         $data = $response->json();
    //         if (!empty($data['data'])) {
    //             $mChapter = new Chapter();
    //             $dataSave = [
    //                 'title' =>  $data['data']['title'],
    //                 'slug' =>   Str::slug($data['data']['title']),
    //                 'content' => $data['data']['content'],
    //                 'order' => $data['data']['number'],
    //                 'storie_id' => $storie, // Use the actual story_id from the API response
    //                 'status' => 2,
    //             ];
    //             // Save the chapter
    //             $savedChapter = $mChapter->store($dataSave);
    //             $savedId = $savedChapter->id;
    //         }
    //     } else {
    //         $errorMessage = $response->status() . ': ' . $response->body();
    //         dd($errorMessage);
    //     }
    //     return $savedId; // Return the ID of the saved record
    // }

}
