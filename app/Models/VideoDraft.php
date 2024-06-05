<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VideoCategory;


class VideoDraft extends Model
{
    use HasFactory;    
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'video_drafts';

    /**
     * Get all of the categories for the Video
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(VideoCategory::class, 'videoId', '_id');
    }
}
