<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'slug', 'title', 'author', 'description', 'image_cover','image_future','status','created_at','updated_at','deleted_at'];


    public function chapters(){
        return $this->hasMany('App\Models\Chapter', 'storie_id')->orderBy('order','DESC');;
    }

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genres', 'storie_genre');
    }
    protected function checkIsset($slug)
    {
        return Stories::where('slug', $slug)->exists();
    }

    public function store($data)
    {
        $checkIsset = $this->checkIsset($data['slug']);
        if ($checkIsset === false) {
            $story = Stories::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'author' => $data['author'],
                'description' => $data['description'],
                'image_cover' => $data['image_cover'],
                'image_future' => $data['image_future'],
                'status' => $data['status'],
            ]);
            foreach ($data['genres'] as $genreId) {
                $story->genres()->attach([$genreId => ['genres_id' => $genreId]]);
            }
            return $story;
        }

        if ($checkIsset === true) {
            return 'Post Existed';
        }
    }


    public function getDetailStore($id){
        $mStories = Stories::where('id', $id)
                    ->with('chapters:storie_id,title,created_at,updated_at', 'genres')
                    ->first();
        return $mStories;
    }
    public function getListStore(array $filter = []){
        $mStories = Stories::orderBy('created_at', 'DESC')
                    ->with('genres')
                    ->withCount('chapters')
                    ->get();
        return $mStories;
    }
}
