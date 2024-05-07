<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class UserShort extends Model
{
    use HasFactory;

    use HasFactory;
    protected $guarded = [];
    protected $appends = ['full_path_thmb_image','full_path_video'];
	protected $connection = 'mongodb';
	protected $collection = 'user_shorts';
	protected $casts = [
    	'title' => 'string',
    	'slug' => 'string',
    	'description' => 'string',
    	// 'category' => 'string',
    	// 'taging' => 'string',
    	'thumbnail_image' => 'string',
    	'video' => 'string',
    	'status' => 'string',
    ];
    public function getFullPathThmbImageAttribute (){
        if (array_key_exists('thumbnail_image', $this->attributes) && (!empty($this->attributes['thumbnail_image']))) {
            return url('storage/shorts/image/'.$this->attributes['thumbnail_image']);
        } else {
            return url('images/no-image.png');
        }
    }
    public function getFullPathVideoAttribute (){
        if (array_key_exists('video', $this->attributes) && (!empty($this->attributes['video']))) {
            return url('storage/shorts/video/'.$this->attributes['video']);
        } else {
            return url('images/no-image.png');
        }
    }
}
