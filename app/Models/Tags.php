<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tags extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'slug', 'status', 'created_at', 'updated_at'];

    /**
     * A tag can belong to many stories.
     */
    public function stories()
    {
        return $this->belongsToMany('App\Models\Stories', 'storie_tag');
    }

    /**
     * Check if a tag exists by its slug.
     */
    protected function checkTagExists($slug)
    {
        return self::where('slug', $slug)->exists();
    }

    /**
     * Store a new tag if it doesn't already exist.
     */
    public function store(array $data)
    {
        if (!$this->checkTagExists($data['slug'])) {
            return self::create([
                'title' => $data['title'],
                'slug' => $data['slug'] ?? Str::slug($data['title']), // Generate slug if not provided
                'status' => $data['status'] ?? '', // Set default status if not provided
            ]);
        }

        return 'Tag already exists';
    }

    /**
     * Retrieve a paginated list of tags with the number of associated stories.
     */
    public function getList(array $filter = [])
    {
        $limit = $filter['limit_paginate'] ?? 8;

        return self::query()
            ->latest()
            ->withCount('stories')
            ->paginate($limit);
    }

    /**
     * Get IDs of existing tags or create new ones if they don't exist.
     */
    public function getIdsOrCreate(array $items)
    {
        $ids = [];

        foreach ($items as $item) {
            $tag = $this->where('title', $item)->first();
            if ($tag) {
                $ids[] = $tag->id;
            } else {
                $newTag = $this->create([
                    'title' => $item,
                    'slug' => Str::slug($item),
                    'status' => 2
                ]);
                $ids[] = $newTag->id;
            }
        }

        return $ids;
    }
}
