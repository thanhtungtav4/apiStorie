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

    public function store($data)
    {
        $chaper = Chapter::firstOrNew([
            'storie_id' => $data['storie_id'],
            'order' => $data['order'],
        ]);

        $chaper->slug = $data['slug'];
        $chaper->title = $data['title'];
        $chaper->content = $data['content'];
        $chaper->status = $data['status'];
        $chaper->save();

        return $chaper;
    }

    public function getDetailChapter($id){
        $mStories = Chapter::where('id', $id)
                    ->first();
        return $mStories;
    }
}
