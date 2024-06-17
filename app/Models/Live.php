<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Jenssegers\Mongodb\Relations\HasMany;
use App\Models\LiveLike;
use App\Models\LiveComment;
use App\Models\LiveView;
use MongoDB\BSON\ObjectID;



class Live extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'lives';

    /**
     * Get all of the likes for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes() : HasMany
    {
        return $this->hasMany(LiveLike::class, 'liveId', '_id');
    }

    /**
     * Get all of the comments for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() : HasMany
    {
        return $this->hasMany(LiveComment::class, 'liveId', '_id');
    }

    /**
     * Get the user that owns the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }

    /**
     * Get all of the views for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(LiveView::class, 'liveId', '_id');
    }
}
