<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagsRequest;
use App\Models\Tags;
use Illuminate\Support\Str;

class TagsController extends Controller
{
    public function create(TagsRequest $request){
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);
        $mTags = new Tags();
        $result = $mTags->store($data);
        if ($result instanceof Tags) {
            return response()->json(['message' => 'Create Tag Success'], 201);
        } elseif ($result === 'Tag already exists') {
            return response()->json(['error' => 'Tag Existed'], 409);
        } else {
            return response()->json(['error' => 'Tag failed to create'], 500);
        }
    }


    public function lists(){
        $mTags = new Tags();
        $result = $mTags->getList();
        return response()->json($result);
    }

    
}
