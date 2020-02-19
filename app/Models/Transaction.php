<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Presenters\TransactionPresenter;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $ext_id
 * @property bool $successful
 * @property string|null $type
 * @property string|null $transactable_type
 * @property int|null $transactable_id
 * @property \App\Support\Money\Money $amount
 * @property string|null $currency
 * @property string|null $card_type
 * @property int|null $card_digits
 * @property int|null $card_exp_month
 * @property int|null $card_exp_year
 * @property string|null $card_token
 * @property string|null $cardholder_name
 * @property string|null $cardholder_email
 * @property string|null $error_code
 * @property \Illuminate\Support\Carbon|null $made_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \Eloquent|\Illuminate\Database\Eloquent\Model $transactable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction query()
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use TransactionPresenter;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'ext_id', 'successful', 'type', 'transactable_type', 'transactable_id',
        'amount', 'currency',
        'card_type', 'card_digits', 'card_exp_month', 'card_exp_year', 'card_token',
        'cardholder_name', 'cardholder_email',
        'error_code',
        'made_at'
    ];

    protected $casts = [
        'successful' => 'boolean'
    ];

    protected $dates = ['made_at'];

    const TYPE_PAYMENT = 'payment';

    const TYPE_REFUND = 'refund';

    //--------------------------------------------------------------------------
    // Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactable()
    {
        return $this->morphTo('transactable');
    }
}
