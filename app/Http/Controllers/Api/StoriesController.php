<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetStoriesRequest;
use App\Http\Requests\StoriesRequest;
use Illuminate\Http\Request;
use App\Models\Stories;
use Illuminate\Support\Str;

class StoriesController extends Controller
{
    public function lists(Request $request){
        $mStories = new Stories();
        $filter = $request->all();
        if (isset($filter['search']) && $filter['search'] == null) {
            unset($filter['search']);
        }
        if (isset($filter['chapter']) && $filter['chapter'] == null) {
            unset($filter['chapter']);
        }
        if (isset($filter['limit']) && $filter['limit'] == null) {
            unset($filter['limit']);
        }
        if (isset($filter['genre']) && $filter['genre'] == null) {
            unset($filter['genre']);
        }
        $oData = $mStories->getListStore($filter);
        return response()->json($oData, 200);
    }

    public function create(StoriesRequest $request){
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);
        $data['genres'] = json_decode($data['genres'], true);
        $data['tags'] = json_decode($data['tags'], true);
        $mStories = new Stories();
        $result = $mStories->store($data);
        if ($result instanceof Stories) {
            return response()->json(['id' => $result->id, 'message' => 'Create Success'], 201);
        } elseif ($result === 'Post Existed') {
            return response()->json(['error' => 'Post Existed'], 409);
        } else {
            return response()->json(['error' => 'Failed to create'], 500);
        }
    }


    public function detail(GetStoriesRequest $request){
        $dataRequest = $request->validated();
        $mStories = new Stories();
        $result = $mStories->getDetailStore($dataRequest['id']);
        return response()->json($result, 200);
    }
}
