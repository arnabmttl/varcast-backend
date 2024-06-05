<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserChat extends Model
{
    use HasFactory;
    protected $guarded = [];
	protected $connection = 'mongodb';
	protected $collection = 'user_chats';
    protected $casts = [
        'senderId' => 'string',
        'receiverId' => 'string'
    ];

}
