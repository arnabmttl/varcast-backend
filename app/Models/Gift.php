<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;
    protected $guarded = [];
	protected $connection = 'mongodb';
	protected $collection = 'gifts';

    protected $casts = [
    	'gift_name' => 'string',
    	'icon_image' => 'string',
    	'coin_value' => 'string',
    	'status' => 'string',
    ];
}
