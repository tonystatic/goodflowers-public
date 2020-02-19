<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Presenters\FlowerPresenter;
use App\Support\Models\HasHashId;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Flower.
 *
 * @property int $id
 * @property string|null $hash_id
 * @property int|null $donation_id
 * @property int|null $color
 * @property string|null $shape
 * @property string|null $file_path
 * @property bool $optimized
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Donation|null $donation
 * @property-read string $file_url
 * @property-read string|null $file_full_path
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flower query()
 * @mixin \Eloquent
 */
class Flower extends Model
{
    use HasHashId;
    use FlowerPresenter;

    protected $table = 'flowers';

    protected $fillable = [
        'hash_id',
        'donation_id',
        'shape', 'color',
        'file_path',
        'optimized'
    ];

    protected $casts = [
        'color'     => 'integer',
        'optimized' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Relations

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }
}
