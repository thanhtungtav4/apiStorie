<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'slug', 'title', 'author', 'order', 'description', 'image_cover','image_future','status','created_at','updated_at','deleted_at'];


    public function chapters(){
        return $this->hasMany('App\Models\Chapter', 'storie_id')->orderBy('order','DESC');;
    }

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genres', 'storie_genre');
    }
    public function store($data)
    {
        $story = Stories::firstOrNew([
            'title' => $data['title'],
            'slug' => $data['slug'],
        ]);
        $story->author = $data['author'];
        $story->description = $data['description'];
        $story->image_cover = $data['image_cover'];
        $story->image_future = $data['image_future'];
        $story->status = $data['status'];
        $story->save();
        if (is_array($data['genres'])) {
            foreach ($data['genres'] as $genreId) {
                $story->genres()->attach([$genreId => ['genres_id' => $genreId]]);
            }
        }
        return $story;
    }


    public function getDetailStore($id){
        $mStories = Stories::where('id', $id)
                    ->with('chapters:id,storie_id,order,title,slug,created_at,updated_at', 'genres')
                    ->first();
        return $mStories;
    }

    public function getListStore(array $filter = [])
    {
        $limitPaginate = $filter['limit_paginate'] ?? 8;

        $mStories = Stories::query()
            ->orderByDesc('created_at')
            ->with(['genres'])
            ->withCount('chapters');
        if (!empty($filter['search'])) {
            $searchTerm = '%' . $filter['search'] . '%';
            $mStories->where("title", 'like', $searchTerm);
        }

        if (!empty($filter['genre'])) {
            $genreName = '%' . $filter['genre'] . '%';
            $mStories->whereHas('genres', function ($query) use ($genreName) {
                $query->where('title', 'like', $genreName);
            });
        }

        if (!empty($filter['cursor_paginate']) && $filter['cursor_paginate'] === true) {
            return $mStories->cursorPaginate($limitPaginate);
        }

        return $mStories->paginate($limitPaginate);
    }
}
