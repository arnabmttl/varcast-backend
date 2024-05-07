<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['full_path_image'];
    protected $connection = 'mongodb';
    protected $collection = 'contents';
    public function getFullPathImageAttribute (){
        if (array_key_exists('image', $this->attributes) && (!empty($this->attributes['image']))) {
            return url('storage/content/'.$this->attributes['image']);
        } else {
            return url('images/no-image.png');
        }
    }
}
