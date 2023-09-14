<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlStories extends Model
{
    use HasFactory;
    protected $table = 'crawl_stories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'type', 'by_id', 'name', 'chapter_count' , 'status', 'status_chapter', 'stories_id', 'stories_save_id', 'created_at','updated_at'];
    
    public function store($data)
    {
        $story = CrawlStories::firstOrNew([
            'type' => $data['type'],
            'by_id' => $data['by_id'],
        ]);
        $story->name = $data['name'];
        $story->chapter_count = $data['chapter_count'];
        $story->status = $data['status'];
        $story->save();
        return $story->id;
    }

    // public function updateIdStories($id){

    // }


}




