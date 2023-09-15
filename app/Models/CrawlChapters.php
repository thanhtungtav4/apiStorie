<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlChapters extends Model
{
    use HasFactory;
    protected $table = 'crawl_chapers';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'chaper_number', 'name', 'crawl_stories_id', 'stories_save_id', 'status', 'created_at','updated_at'];

    public function store($data)
    {
        $chaper = CrawlChapters::firstOrNew([
            'chaper_number' => $data['chaper_number'],
            'crawl_stories_id' => $data['crawl_stories_id'],
        ]);
        $chaper->stories_save_id = $data['stories_save_id'];
        $chaper->name = $data['name'];
        $chaper->status = $data['status'];
        $chaper->save();

        return $chaper;
    }
}
