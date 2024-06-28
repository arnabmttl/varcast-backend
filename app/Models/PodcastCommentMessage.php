<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PodcastComment;
use App\Models\Podcast;
use App\Models\User;


class PodcastCommentMessage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $table = "podcast_comment_messages";

    /**
     * Get the comment that owns the PodcastCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(PodcastComment::class, 'commentId', '_id');
    }

    /**
     * Get the podcast that owns the PodcastCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class, 'podcastId', '_id');
    }

    /**
     * Get the user that owns the PodcastCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }
}
