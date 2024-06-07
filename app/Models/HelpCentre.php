<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class HelpCentre extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $connection = 'mongodb';
    protected $collection = 'help_centres';
}
