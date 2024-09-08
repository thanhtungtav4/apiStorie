<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Http\Requests\GetChapterRequest;
use Illuminate\Http\Request;
use App\Models\Chapter;
use Illuminate\Support\Str;


class ChapterController extends Controller
{
    public function create(ChapterRequest $request){
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);
        $mChapter = new Chapter();
        $result = $mChapter->store($data);
        if ($result instanceof Chapter) {
            return response()->json(['message' => 'Create Success'], 201);
        }else {
            return response()->json(['error' => 'Failed to create'], 500);
        }
    }
    public function detail(GetChapterRequest $request){
        $dataRequest = $request->validated();
        $mStories = new Chapter();
        $result = $mStories->getDetailChapter($dataRequest['id']);
        return response()->json($result, 200);
    }
}
