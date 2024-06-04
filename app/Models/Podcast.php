<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PodcastLike;
use App\Models\PodcastComment;
use App\Models\PodcastView;
use App\Models\User;


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

    /**
     * Get the user that owns the Podcast
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }

    /**
     * Get all of the views for the Podcast
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(PodcastView::class, 'podcastId', '_id');
    }
}
