<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Presenters\DonationPresenter;
use App\Support\Models\HasHashId;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Donation.
 *
 * @property int $id
 * @property string|null $hash_id
 * @property int|null $garden_id
 * @property int|null $user_id
 * @property int|null $transaction_id
 * @property bool $complete
 * @property \App\Support\Money\Money $amount
 * @property int $flowers_quantity
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Flower[]|\Illuminate\Database\Eloquent\Collection $flowers
 * @property-read int|null $flowers_count
 * @property-read \App\Models\Garden|null $garden
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Transaction[]|\Illuminate\Database\Eloquent\Collection $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donation query()
 * @mixin \Eloquent
 */
class Donation extends Model
{
    use HasHashId;
    use DonationPresenter;

    protected $table = 'donations';

    protected $fillable = [
        'hash_id',
        'garden_id', 'user_id',
        'complete',
        'amount', 'flowers_quantity',
        'email'
    ];

    protected $casts = [
        'complete'         => 'boolean',
        'flowers_quantity' => 'integer'
    ];

    //--------------------------------------------------------------------------
    // Relations

    public function garden()
    {
        return $this->belongsTo(Garden::class, 'garden_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function flowers()
    {
        return $this->hasMany(Flower::class, 'donation_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }
}
