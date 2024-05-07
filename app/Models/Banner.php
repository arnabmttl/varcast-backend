<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['full_path_image'];
    protected $connection = 'mongodb';
    protected $collection = 'banners';
    public function vendor() {
        return $this->hasOne('App\Models\User','id', 'vendor_id')->where('status','!=','D');
    }
    public function getFullPathImageAttribute (){
		if (array_key_exists('image', $this->attributes) && (!empty($this->attributes['image']))) {
			return url('storage/banner/'.$this->attributes['image']);
		} else {
			return url('images/no-image.png');
		}
	}
}
