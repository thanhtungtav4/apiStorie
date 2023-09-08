<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\getStoriesRequest;
use App\Http\Requests\storiesRequest;
use Illuminate\Http\Request;
use App\Models\Stories;
use Illuminate\Support\Str;

class storiesController extends Controller
{
    public function lists(Request $request){
        $mStories = new Stories();
        $filter = $request->all();
        if (isset($filter['status']) && $filter['status'] == -1) {
            unset($filter['status']);
        }
        if (isset($filter['title']) && $filter['title'] == null) {
            unset($filter['title']);
        }
        if (isset($filter['chapters']) && $filter['chapters'] == null) {
            unset($filter['chapters']);
        }
        if (isset($filter['chapters']) && $filter['chapters'] == null) {
            unset($filter['chapters']);
        }
        $oData = $mStories->getListStore($filter);
        return response()->json($oData, 200);
    }

    public function create(storiesRequest $request){
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);
        $data['genres'] = json_decode($data['genres'], true);
        $mStories = new Stories();
        $result = $mStories->store($data);
        if ($result instanceof Stories) {
            return response()->json(['message' => 'Create Success'], 201);
        } elseif ($result === 'Post Existed') {
            return response()->json(['error' => 'Post Existed'], 409);
        } else {
            return response()->json(['error' => 'Failed to create'], 500);
        }
    }


    public function detail(getStoriesRequest $request){
        $dataRequest = $request->validated();
        $mStories = new Stories();
        $result = $mStories->getDetailStore($dataRequest['id']);
        return response()->json($result, 200);
    }


}
