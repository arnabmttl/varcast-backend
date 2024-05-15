<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Follow extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'follows';

    /**
     * Get the user that owns the PodcastLike
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followings(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }

    /**
     * Get the user that owns the PodcastLike
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authId', '_id');
    }
}
