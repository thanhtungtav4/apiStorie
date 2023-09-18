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

    public function getAll(){
        $chaper = CrawlChapters::select('id', 'chaper_number', 'crawl_stories_id', 'stories_save_id', 'status')
                  ->where('status', 2)->get();
        return $chaper;
    }

    public function updateStatus($id){
        // Use the find method to retrieve the record by ID
        $crawlChapter = CrawlChapters::find($id);
    
        if ($crawlChapter) {
            // Update the 'status' column with the new value
            $crawlChapter->status = 3;
    
            // Save the changes to the database
            $crawlChapter->save();
            
            // Optionally, you can return the updated record or a success message
            return $crawlChapter;
        } else {
            // Handle the case where the record with the given ID doesn't exist
            return "Record not found";
        }
    }
    
}
