<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'gender', 'is_active', 'staff_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public $incrementing = false;

    public function setGenderAttribute($value){
        $this->attributes['gender'] = strtolower($value);
    }

    public function scopeActive($query){
        return $query->where('is_active', true);
    }

    public function scopeHasRole($query, ...$roles){

        $roles = array_map(function($role) use ($roles){
            return strtolower($role);
        }, $roles);

        return $query->whereHas('roles',
            function ($query) use ($roles){
                return $query->whereIn('name', $roles);
        });
    }

    public function scopeIsStaff($query){

        $roles = Helper::getStaffRoles();

        return $this->scopeHasRole($query, ...$roles);
    }

    public function roles(){
        return $this->belongsToMany('App\Role');
    }

    public function branchDetail(){
        return $this->hasOne('App\BranchUser')->with('branch');
    }

    //Add UUID as Id when creating user
    public static function boot()
    {
        self::creating(function ($user) {
            $user->id = (string) Uuid::generate(4);
            return $user;
        });
    }
}
