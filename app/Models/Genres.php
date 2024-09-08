<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genres extends Model
{
    use HasFactory;
    protected $table = 'genres';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'title', 'slug', 'status','created_at','updated_at','deleted_at' ];

    public function stories()
    {
        return $this->belongsToMany('App\Models\Stories', 'storie_genre');
    }
    protected function checkGenresIsset($slug)
    {
        return Genres::where('slug', $slug)->exists();
    }
    public function store($data){
        $Checkisset = $this->checkGenresIsset($data['slug']);
        if($Checkisset === false){
            $mSave = Genres::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'status'=> $data['status'],
             ]);
            return $mSave;
        }
        if($Checkisset == true){
            return 'Genres Existed';
        }
    }


    public function getList(array $filter = [])
    {
        $limitPaginate = $filter['limit_paginate'] ?? 8;

        return Genres::query()
            ->latest()
            ->withCount('stories')
            ->paginate($limitPaginate);
    }

    /**
     * Get IDs of existing genres or create new ones if they don't exist.
     */
    public function getIdsOrCreate(array $items)
    {
        $ids = [];

        foreach ($items as $item) {
            if (is_numeric($item)) {
                $genre = $this->find($item);
                if ($genre) {
                    $ids[] = $genre->id;
                }
            } else {
                $genre = $this->where('title', $item)->first();
                if ($genre) {
                    $ids[] = $genre->id;
                } else {
                    $newGenre = $this->create(['title' => $item, 'slug' => Str::slug($item)]);
                    $ids[] = $newGenre->id;
                }
            }
        }

        return $ids;
    }
    
}
