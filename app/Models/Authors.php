<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Authors extends Model
{
    use HasFactory;
    
    protected $table = 'authors';
    protected $primaryKey = 'id';
    
    // Removed 'id', 'created_at', and 'updated_at' from $fillable
    protected $fillable = ['slug', 'name', 'bio', 'profile_picture', 'original_name'];

    public function storie()
    {
        return $this->belongsTo('App\Models\Stories', 'author_id');
    }

    protected function checkAuthorExists($slug)
    {
        return self::where('slug', $slug)->exists();
    }

    /**
     * Store or update an author.
     */
    public function store($data)
    {
        // Check if an author with the same slug already exists
        if ($this->checkAuthorExists($data['slug'])) {
            return 'Author already exists';
        }

        $author = self::firstOrNew(['slug' => $data['slug']]);
        $author->name = $data['name'];
        $author->original_name = $data['original_name'] ?? null;
        $author->bio = $data['bio'] ?? null;
        $author->profile_picture = $data['profile_picture'] ?? null;
        $author->save();

        return $author;
    }

    /**
     * Get an author by ID 
     */
    public function getDetailAuthor($id)
    {
        return self::find($id);
    }

    /**
     * Get IDs of existing authors or create new ones if they don't exist.
     */
    public function getIdsOrCreateAuthor($item)
    {
        if (is_numeric($item)) {
            // Find by ID
            $author = $this->find($item);
            if ($author) {
                return $author->id; // Return the found ID
            }
        } else {
            // Find by name
            $author = $this->where('name', $item)->first();
            if ($author) {
                return $author->id; // Return the found ID
            } else {
                // Create a new author
                $newAuthor = $this->create([
                    'name' => $item,
                    'slug' => Str::slug($item)
                ]);
                return $newAuthor->id; // Return the created ID
            }
        }
    
        return null; // Return null if no author found or created
    }
    
}
