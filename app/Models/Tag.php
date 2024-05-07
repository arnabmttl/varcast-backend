<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = [];
	protected $connection = 'mongodb';
	protected $collection = 'tags';
	protected $casts = [
    	'name' => 'string',
    	'slug' => 'string',
    	'is_order' => 'integer',
    	'status' => 'string',
    ];
}
