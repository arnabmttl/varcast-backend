<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoComment;

class VideoCommentMessage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'video_comment_messages';

    /**
     * Get the user that owns the VideoCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }

    /**
     * Get the video that owns the VideoCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'videoId', '_id');
    }

    /**
     * Get the comment that owns the VideoCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(VideoComment::class, 'commentId', '_id');
    }
}
