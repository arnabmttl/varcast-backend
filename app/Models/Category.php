<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VideoCategory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'categories';
    public function parentCategory() {
        return $this->hasOne('App\Models\Category',  '_id', 'parent_id');
    }
    public function childCategory() {
        return $this->hasMany('App\Models\Category', 'parent_id', '_id')->where('status','!=','D')->orderBy('name','asc');
    }

    /**
     * for active childrens
     */
    public function activeChildCategory() {
        return $this->hasMany('App\Models\Category', 'parent_id', '_id')->where('deleted_at', null)->where('status', 'A')->orderBy('name','asc');
    }

    public function getFullPathImageAttribute (){
        if (array_key_exists('image', $this->attributes) && (!empty($this->attributes['image']))) {
            return url('storage/category/'.$this->attributes['image']);
        } else {
            return url('images/no-image.png');
        }
    }

    /**
     * Get all of the videos for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(VideoCategory::class, 'categoryId', '_id');
    }
}
