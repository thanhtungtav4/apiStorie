<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenresRequest;
use App\Models\Genres;
use Illuminate\Support\Str;

class GenresController extends Controller
{
    public function create(GenresRequest $request){
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

    public function lists(){
        $mGenres = new Genres();
        $result = $mGenres->getList();
        return response()->json($result);
    }

    
}
