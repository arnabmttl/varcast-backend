<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\VideoDraft;


class VideoDraftCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'video_draft_categories';

    /**
     * Get the videodraft that owns the VideoDraftCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function videodraft(): BelongsTo
    {
        return $this->belongsTo(VideoDraft::class, 'videoDraftId', '_id');
    }

    /**
     * Get the category that owns the VideoDraftCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoryId', '_id');
    }


}
