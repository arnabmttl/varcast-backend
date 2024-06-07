<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PlaylistMedia;

class Playlist extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'playlists';

    /**
     * Get all of the media for the Playlist
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media(): HasMany
    {
        return $this->hasMany(PlaylistMedia::class, 'playlistId', '_id');
    }

    
}
