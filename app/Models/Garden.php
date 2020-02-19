<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Presenters\GardenPresenter;
use App\Support\Models\HasHashId;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Garden.
 *
 * @property int $id
 * @property string|null $hash_id
 * @property string|null $slug
 * @property string|null $owner_name
 * @property string|null $owner_link
 * @property string|null $owner_avatar_path
 * @property \App\Support\Money\Money $total_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Donation[]|\Illuminate\Database\Eloquent\Collection $donations
 * @property-read int|null $donations_count
 * @property-read string $owner_avatar_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Garden newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Garden newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Garden query()
 * @mixin \Eloquent
 */
class Garden extends Model
{
    use HasHashId;
    use GardenPresenter;

    protected $table = 'gardens';

    protected $fillable = [
        'hash_id', 'slug',
        'owner_name', 'owner_link', 'owner_avatar_path',
        'total_value'
    ];

    protected $casts = [
        'total_value' => 'integer'
    ];

    //--------------------------------------------------------------------------
    // Relations

    public function donations()
    {
        return $this->hasMany(Donation::class, 'garden_id');
    }
}
