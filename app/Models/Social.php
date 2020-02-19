<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Social.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $provider
 * @property string|null $ext_id
 * @property string|null $email
 * @property string|null $name
 * @property string|null $link
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social query()
 * @mixin \Eloquent
 */
class Social extends Model
{
    protected $table = 'socials';

    protected $fillable = [
        'user_id',
        'provider', 'ext_id', 'email', 'name', 'link',
        'last_login_at'
    ];

    protected $dates = ['last_login_at'];

    const PROVIDER_FACEBOOK = 'fb';

    const PROVIDER_INSTAGRAM = 'ig';

    const PROVIDER_VK = 'vk';

    const PROVIDER_TWITTER = 'tw';

    //--------------------------------------------------------------------------
    // Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //--------------------------------------------------------------------------
    // Other

    public static function getSupportedProviders() : array
    {
        return [
            self::PROVIDER_FACEBOOK, self::PROVIDER_INSTAGRAM, self::PROVIDER_VK
        ];
    }
}
