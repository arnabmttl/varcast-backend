<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Gift;
use App\Models\Podcast;

class UserCoin extends Model
{
    use HasFactory;

    protected $guarded = [];
	protected $connection = 'mongodb';
	protected $collection = 'user_coins';

    /**
     * Get the gift that owns the UserCoin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gift(): BelongsTo
    {
        return $this->belongsTo(Gift::class, 'giftId', '_id');
    }

    /**
     * Get the podcast that owns the UserCoin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class, 'podcastId', '_id');
    }

    
}
