<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class CoinPrice extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'coin_prices';

    protected $casts = [
    	'plan_name' => 'string',
    	'from_coin' => 'integer',
    	'price' => 'decimal:2',
    	'sale_price' => 'decimal:2',
    	'status' => 'string',
    ];
}
