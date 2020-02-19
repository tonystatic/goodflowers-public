<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User.
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $name
 * @property string|null $link
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_active_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Social[]|\Illuminate\Database\Eloquent\Collection $socials
 * @property-read int|null $socials_count
 * @property-read \Illuminate\Notifications\DatabaseNotification[]|\Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email', 'name', 'link',
        'last_active_at',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $dates = ['last_active_at'];

    //--------------------------------------------------------------------------
    // Relations

    public function socials()
    {
        return $this->hasMany(Social::class, 'user_id');
    }
}
