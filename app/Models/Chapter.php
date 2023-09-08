<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $table = 'chapters';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'storie_id', 'order', 'title', 'slug', 'content','status','created_at','updated_at', 'deleted_at'];

    public function storie()
    {
        return $this->belongsTo('App\Models\Stories', 'storie_id');
    }

    public function store($data){
        $mSave = Chapter::create([
            'storie_id' => $data['storie_id'],
            'slug' => $data['slug'],
            'order' => $data['order'],
            'title' => $data['title'],
            'content' => $data['content'],
            'status'=> $data['status'],
         ]);
        return $mSave;
    }

    public function getDetailChapter($id){
        $mStories = Chapter::where('id', $id)
                    ->first();
        return $mStories;
    }
}
