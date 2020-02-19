<?php

declare(strict_types=1);

namespace App\Models\Presenters;

use Storage;

trait FlowerPresenter
{
    /**
     * @return string
     */
    public function getFileUrlAttribute() : string
    {
        return Storage::drive('public')->url($this->file_path) . '?' . $this->updated_at->timestamp;
    }

    /**
     * @return string
     */
    public function getFileFullPathAttribute() : string
    {
        return Storage::drive('public')->path($this->file_path);
    }
}
