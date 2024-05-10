<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PodcastLike;
use App\Models\PodcastComment;


class Podcast extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'podcasts';

    /**
     * Get all of the likes for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(PodcastLike::class, 'podcastId', '_id');
    }

    /**
     * Get all of the comments for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PodcastComment::class, 'podcastId', '_id');
    }
}
