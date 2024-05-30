<?php

namespace App\Models;

use App\Permissions\HasPermissionsTrait;
use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable, DianujHashidsTrait, HasPermissionsTrait, SoftDeletes;

    protected $guard = 'admin';

    protected $table = 'admins';

    protected $casts = [
        'user_permissions' => 'object',
    ];

    protected $fillable = ['first_name', 'last_name', 'username', 'password', 'email',  'user_permissions', 'user_type', 'image', 'is_active'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return ucwords($this->first_name.' '. $this->last_name);
    }

    public function getIsAdminAttribute()
    {
        return $this->user_type == 'admin';
    }

    // public function getIsMonitorAttribute()
    // {
    //     return $this->user_type == 'monitor';
    // }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function is_admin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user_type == 'admin',
        );
    }
}
