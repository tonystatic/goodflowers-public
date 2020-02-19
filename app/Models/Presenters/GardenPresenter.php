<?php

declare(strict_types=1);

namespace App\Models\Presenters;

use App\Support\Money\Money;
use Storage;

trait GardenPresenter
{
    /**
     * @return string
     */
    public function getOwnerAvatarUrlAttribute() : string
    {
        return Storage::drive('public')->url($this->owner_avatar_path) . '?' . $this->updated_at->timestamp;
    }

    /**
     * @param int $value
     * @return \App\Support\Money\Money
     */
    public function getTotalValueAttribute(int $value) : Money
    {
        return Money::fromAmount($value);
    }
}
