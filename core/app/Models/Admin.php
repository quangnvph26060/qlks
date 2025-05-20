<?php

namespace App\Models;
///
use App\Traits\GlobalStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
    use GlobalStatus; 
    protected $guarded = [];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
