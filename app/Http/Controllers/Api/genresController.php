<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\genresRequest;
use App\Models\Genres;
use Illuminate\Support\Str;

class genresController extends Controller
{
    public function create(genresRequest $request){
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);
        $mGenres = new Genres();
        $result = $mGenres->store($data);
        if ($result instanceof Genres) {
            return response()->json(['message' => 'Create Genre Success'], 201);
        } elseif ($result === 'Post Existed') {
            return response()->json(['error' => 'Genre Existed'], 409);
        } else {
            return response()->json(['error' => 'Genre failed to create'], 500);
        }
    }
}
