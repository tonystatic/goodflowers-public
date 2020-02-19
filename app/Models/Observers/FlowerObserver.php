<?php

declare(strict_types=1);

namespace App\Models\Observers;

use App\Models\Flower;
use App\Support\Files\Storage;

class FlowerObserver
{
    /**
     * @param \App\Models\Flower $flower
     */
    public function deleting(Flower $flower) : void
    {
        /** @var \App\Support\Files\Storage $storage */
        $storage = app(Storage::class);

        $storage->deleteFromPath($flower->file_path, DISK_PUBLIC, true);
    }
}
