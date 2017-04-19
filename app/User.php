<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
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

    /**
     * Wrapper for the uuid.
     */
    public function save(array $options = []) {
        $this->uuid = property_exists($this, 'uuid') && is_string($this->uuid) && preg_match('#^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$#', $this->uuid)
          ? $this->uuid
          : Uuid::generate(4)
        ;
        return parent::save($options);
    }
}
