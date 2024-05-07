<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Podcast extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'podcasts';
}
