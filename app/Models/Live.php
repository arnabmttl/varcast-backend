<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Jenssegers\Mongodb\Relations\HasMany;
// use MongoDB\Laravel\Relations\HasMany;
use App\Models\LiveLike;
use App\Models\LiveComment;



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
    public function likes(): HasMany
    {
        return $this->hasMany(LiveLike::class, 'liveId', '_id');
    }

    /**
     * Get all of the comments for the Live
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(LiveComment::class, 'liveId', '_id');
    }
}
