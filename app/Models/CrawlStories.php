<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlStories extends Model
{
    use HasFactory;
    protected $table = 'crawl_stories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'type', 'byID', 'name', 'chapter_count' , 'status', 'created_at','updated_at'];
    public function store($data){
        $mSave = Genres::create([
            'type' => $data['type'],
            'byID' => $data['byID'],
            'name' => $data['name'],
            'chapter_count' => $data['chapter_count'],
            'status'=> $data['status'],
            ]);
        return $mSave;
    }
}
