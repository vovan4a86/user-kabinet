<?php

namespace App;

use App\Traits\HasImage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string image
 */
class User extends Authenticatable
{
    use Notifiable, HasImage;

    const UPLOAD_URL = '/uploads/site_users/';
    const NO_IMAGE = '/adminlte/no_image.png';

    public static $thumbs = [
        1 => '100x50', //admin
        2 => '285x263', //list
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'status'
    ];

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
    ];

    public function getImageSrcAttribute(): string
    {
        if ($this->image) {
            return $this->thumb(2);
        }

        return self::NO_IMAGE;
    }
}
