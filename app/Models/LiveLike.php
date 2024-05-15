<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class LiveLike extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'live_likes';

    /**
     * Get the user that owns the PodcastLike
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }
}
