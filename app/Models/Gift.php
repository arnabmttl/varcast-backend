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
}
