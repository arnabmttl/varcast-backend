<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Live;
use App\Models\LiveComment;

class LiveCommentMessage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'video_comment_messages';

    /**
     * Get the user that owns the LiveCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }

    /**
     * Get the live that owns the LiveCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function live(): BelongsTo
    {
        return $this->belongsTo(Live::class, 'liveId', '_id');
    }

    /**
     * Get the comment that owns the LiveCommentMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(LiveComment::class, 'commentId', '_id');
    }


}
