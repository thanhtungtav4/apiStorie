<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorsRequest;
use App\Http\Requests\AuthorDetailRequest;
use App\Models\Authors;
use Illuminate\Support\Str;

class AuthorsController extends Controller
{
    public function create(AuthorsRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);

        $author = new Authors();
        $result = $author->store($data);

        if ($result instanceof Authors) {
            return response()->json(['message' => 'Create Author Success'], 201);
        } elseif ($result === 'Author already exists') {
            return response()->json(['error' => 'Author Existed'], 409);
        } else {
            return response()->json(['error' => 'Author failed to create'], 500);
        }
    }


    // Get a list of authors
    public function lists()
    {
        $authors = Authors::all();
        return response()->json($authors);
    }
    
    // Get details of a specific author by ID
    public function detail(AuthorDetailRequest $request){
        $dataRequest = $request->validated();
       
        $mAuthor = new Authors();
        $result = $mAuthor->getDetailAuthor($dataRequest['id']);
        return response()->json($result, 200);
    }
    
}
