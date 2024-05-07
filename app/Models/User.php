<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];
    protected $appends = ['full_path_image','full_path_gov_id_card'];
    protected $connection = 'mongodb';
    protected $collection = 'users';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];
    protected $dates = array('created_at');
    public function addressList() {
        return $this->hasMany('App\Models\Address','user_id', 'id')->where('status', 'A')->orderBy('default');
    }
    public function defaultAddress() {
        return $this->hasOne('App\Models\Address','user_id', 'id')->where('status', 'A')->where('default', 'Y');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getFullPathImageAttribute (){
        if (array_key_exists('image', $this->attributes) && (!empty($this->attributes['image']))) {
            return url('storage/profile_pics/'.$this->attributes['image']);
        } else {
            return url('images/no-image.png');
        }
    }
    public function getFullPathGovIdCardAttribute (){
        if (array_key_exists('govt_id_card', $this->attributes) && (!empty($this->attributes['govt_id_card']))) {
            return url('storage/documents/'.$this->attributes['govt_id_card']);
        } else {
            return url('images/no-image.png');
        }
    }

    public function country() {
        return $this->hasOne('App\Models\Country','phonecode', 'country_id')->latest();
    }
}
