<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrawlStories;
use App\Models\CrawlChapters;
use App\Models\Chapter;
use App\Models\Stories;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
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
                        'description' => '',
                        'image_future' => '',
                        'genres' => '[1]',
                        'status' => '1',
                    ];
                    $StorieId = $mStories->store($dataStorie);
                    $dataSave = [
                        'type' => "noveltyt",
                        'chapter_count' => $value['chapter_count'] ?: 0,
                        'name' => $value['title'] ?: null,
                        'by_id' => $value['id'] ?: null,
                        'status' => 2,
                    ];
                    $savedId = $mCrawlStories->store($dataSave);
                    $savedIds[] = $savedId; // Add the saved ID to the array
                }
            }
        } else {
            $errorMessage = $response->status() . ': ' . $response->body();
            dd($errorMessage);
        }

        return $savedIds; // Return the array of saved IDs
    }

    public function getListChapter(){
        $story_id = '59b410d06fc3330b5d062f76';
        $savedIds = []; // Initialize an array to store saved IDs
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get('http://api.noveltyt.net/api/v2/chapters/numbers?end=5000&start=1&story_id=' . $story_id);
            
            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    $mCrawlChapter = new CrawlChapters();
                    foreach ($data['data'] as $value) {
                        $dataStoriesSave = [
                            'chaper_number' => $value['number'],
                            'name' => $value['title'],
                            'crawl_stories_id' => $story_id,
                            'status' => 2,
                        ];
                        $savedId = $mCrawlChapter->store($dataStoriesSave);
                        $savedIds[] = $savedId;
                    }
                }
            } else {
                $errorMessage = $response->status() . ': ' . $response->body();
                dd($errorMessage);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $savedIds; // Return the array of saved IDs
    }

    public function saveChaper(){
        $number = 1;
        $story_id = '64dde4195831d653267d5785';
        $savedId = null; // Initialize the savedId variable
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('http://api.noveltyt.net/api/v2/chapters/detail?number='. $number .'&story_id=' . $story_id);
    
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['data'])) {
                $mChapter = new Chapter();
                $dataSave = [
                    'title' =>  $data['data']['title'],
                    'slug' =>   Str::slug($data['data']['title']),
                    'content' => $data['data']['content'],
                    'order' => $data['data']['number'],
                    'storie_id' => 1, // Use the actual story_id from the API response
                    'status' => 2,
                ];
                
                // Save the chapter
                $savedChapter = $mChapter->store($dataSave);
                $savedId = $savedChapter->id;
            }
        } else {
            $errorMessage = $response->status() . ': ' . $response->body();
            dd($errorMessage);
        }
    
        return $savedId; // Return the ID of the saved record
    }
    
    
}
