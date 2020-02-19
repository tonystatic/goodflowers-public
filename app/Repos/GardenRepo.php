<?php

declare(strict_types=1);

namespace App\Repos;

use App\Models\Garden;

class GardenRepo
{
    /**
     * @param int $id
     * @return \App\Models\Garden|null
     */
    public function findById(int $id) : ?Garden
    {
        return Garden::query()
            ->find($id);
    }

    /**
     * @param string $slug
     * @return \App\Models\Garden|null
     */
    public function findBySlug(string $slug) : ?Garden
    {
        return Garden::query()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param string $slug
     * @return bool
     */
    public function existsBySlug(string $slug) : bool
    {
        return Garden::query()
            ->where('slug', $slug)
            ->exists();
    }

    /**
     * @param array $data
     * @return \App\Models\Garden
     */
    public function createRaw(array $data) : Garden
    {
        return Garden::query()
            ->create($data);
    }

    /**
     * @param \App\Models\Garden $garden
     * @param int $value
     */
    public function updateTotalValue(Garden &$garden, int $value) : void
    {
        $garden->update([
            'total_value' => $value
        ]);
    }
}
