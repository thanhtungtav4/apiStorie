<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'slug', 'title', 'order', 'description', 'author_id', 'image_cover','image_feature','status','created_at','updated_at','deleted_at'];


    public function chapters(){
        return $this->hasMany('App\Models\Chapter', 'storie_id')->orderBy('order','DESC');;
    }

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genres', 'storie_genre');
    }

    
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tags', 'storie_tag');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Authors', 'author_id');
    }
    
    public function store($data)
    {
        $storie = Stories::firstOrNew([
            'title' => $data['title'],
            'slug' => $data['slug'],
        ]);
        $storie->author_id = $data['author_id'] ?? null;
        $storie->description = $data['description'] ?? '';
        $storie->image_cover = $data['image_cover'] ?? '';
        $storie->image_feature = $data['image_feature'] ?? '';
        $storie->status = $data['status'] ?? 0;
        $storie->save();
        if (isset($data['genres']) && is_array($data['genres'])) {
            foreach ($data['genres'] as $genreId) {
                $storie->genres()->attach([$genreId => ['genres_id' => $genreId]]);
            }
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            foreach ($data['tags'] as $tagId) {
                $storie->tags()->attach([$tagId => ['tags_id' => $tagId]]);
            }
        }
        return $storie;
    }


    public function getDetailStore($id){
        $mStories = Stories::where('id', $id)
                    ->with('chapters:id,storie_id,order,title,slug,created_at,updated_at', 'genres', 'tags', 'author')
                    ->first();
        return $mStories;
    }

    public function getListStore(array $filter = [])
    {
        $limitPaginate = $filter['limit_paginate'] ?? 8;

        $mStories = Stories::query()
            ->orderByDesc('created_at')
            ->with(['genres', 'tags', 'author'])
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
