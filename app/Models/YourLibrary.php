<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class YourLibrary extends Model
{
    use HasFactory;
    protected $guarded = [];
	protected $connection = 'mongodb';
	protected $collection = 'your_libraries';
	protected $casts = [
    	'user_id' => 'string',
    	'type' => 'string',
    	'name' => 'string',
    	'slug' => 'string',
    	'author' => 'string',
    	'thumbnail_image' => 'string',
    	'about' => 'integer',
    	'status' => 'string',
    ];
}
