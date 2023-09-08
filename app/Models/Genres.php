<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
