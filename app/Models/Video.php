<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VideoLike;
use App\Models\VideoComment;

class Video extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'videos';

    /**
     * Get all of the likes for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(VideoLike::class, 'videoId', '_id');
    }

    /**
     * Get all of the comments for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'videoId', '_id');
    }
}
