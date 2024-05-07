<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class MyMusic extends Model
{
    use HasFactory;
	protected $guarded = [];
    protected $appends = ['full_path_thmb_image','full_path_file'];
	protected $connection = 'mongodb';
	protected $collection = 'my_music';
	protected $casts = [
    	'name' => 'string',
    	'slug' => 'string',
    	'author' => 'string',
    	'thumbnail_image' => 'string',
    	'file' => 'string',
    	'duration' => 'string',
    	'is_order' => 'integer',
    	'status' => 'string',
    ];

    public function getFullPathThmbImageAttribute (){
        if (array_key_exists('thumbnail_image', $this->attributes) && (!empty($this->attributes['thumbnail_image']))) {
            return url('storage/my_music/thumbnail/'.$this->attributes['thumbnail_image']);
        } else {
            return url('images/no-image.png');
        }
    }
    public function getFullPathFileAttribute (){
        if (array_key_exists('file', $this->attributes) && (!empty($this->attributes['file']))) {
            return url('storage/my_music/file/'.$this->attributes['file']);
        } else {
            return url('images/no-image.png');
        }
    }
}
