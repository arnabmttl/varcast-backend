<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
class Emoji extends Model
{
	use HasFactory;
	protected $guarded = [];
	protected $connection = 'mongodb';
	protected $collection = 'emoji';
	protected $casts = [
    	'emoji' => 'string',
    	'usage_coin' => 'integer',
    	'is_order' => 'integer',
    	'status' => 'string',
    ];
}
