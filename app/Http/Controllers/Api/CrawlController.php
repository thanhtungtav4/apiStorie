<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrawlStories;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CrawlController extends Controller
{
    public function getListInterface()
    {
        $savedIds = []; // Initialize an array to store saved IDs

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get('http://api.noveltyt.net/api/v2/stories/list');

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    $mCrawlStories = new CrawlStories();
                    foreach ($data['data'] as $value) {
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
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $savedIds; // Return the array of saved IDs
    }


}
