<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Video;



class VideoCategory extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'video_categories';
    protected $fillable = ['videoId','categoryId'];

    /**
     * Get the video that owns the VideoCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'videoId', '_id');
    }

    /**
     * Get the category that owns the VideoCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(User::class, 'categoryId', '_id');
    }



}
