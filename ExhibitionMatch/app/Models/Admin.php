<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'Admin_ID';

    protected $fillable = [
        'admin_username',
        'admin_password',
    ];

    protected $hidden = [
        'admin_password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'admin_password' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->admin_password;
    }
}

