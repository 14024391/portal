<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

    public function hasPermission($name){
        return $this->roles()->first()->permissions()->where('name',$name)->count() > 0;
    }

    public function hasRole($name){
        return $this->roles()->where('name',$name)->count() > 0;
    }

    public function isSuperAdmin(){
        return $this->email == 'ashutosh@newgenray.com';
    }

    public function getProfileUrlAttribute($value){
        return $value != null ? $value : '/profiles/profile_default.jpg';
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->setTimezone(Session::get('timezone'));
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->setTimezone(Session::get('timezone'));
    }

}
