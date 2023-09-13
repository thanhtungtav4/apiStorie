<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CrawlController extends Controller
{
   public function getListInterface(){
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->get('http://api.noveltyt.net/api/v2/stories/list');

            if ($response->successful()) {
                // The API request was successful, you can access the response data like this:
                $data = $response->json();

            } else {
                // The API request was not successful, handle the error here
                $errorMessage = $response->status() . ': ' . $response->body();
                dd($errorMessage);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the request
            dd($e->getMessage());
        }
    }
}
