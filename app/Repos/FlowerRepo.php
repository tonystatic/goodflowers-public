<?php

declare(strict_types=1);

namespace App\Repos;

use App\Models\Donation;
use App\Models\Flower;
use App\Models\Garden;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class FlowerRepo
{
    /**
     * @param \App\Models\Donation $donation
     * @param int $color
     * @param string $shape
     * @param string|null $filePath
     * @return \App\Models\Flower
     */
    public function create(Donation $donation, int $color, string $shape, string $filePath = null) : Flower
    {
        /** @var \App\Models\Flower $flower */
        $flower = $donation->flowers()
            ->create([
                'color'     => $color,
                'shape'     => $shape,
                'file_path' => $filePath
            ]);

        return $flower;
    }

    /**
     * @param string $hashId
     * @return \App\Models\Flower|null
     */
    public function findByHashId(string $hashId) : ?Flower
    {
        return Flower::query()
            ->where('hash_id', $hashId)
            ->with('donation', 'donation.garden')
            ->first();
    }

    /**
     * @param \App\Models\Flower $flower
     * @param string|null $filePath
     * @param ?string $filePath
     */
    public function setFilePath(Flower &$flower, ?string $filePath) : void
    {
        $flower->update([
            'file_path' => $filePath
        ]);
    }

    /**
     * @param \App\Models\Flower $flower
     * @param ?string $filePath
     */
    public function touch(Flower &$flower) : void
    {
        $flower->touch();
    }

    /**
     * @param \App\Models\Garden $garden
     * @return \App\Models\Flower[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllActiveInGarden(Garden $garden) : Collection
    {
        return Flower::query()
            ->whereHas('donation', function ($q) use ($garden) : void {
                /* @var \Illuminate\Database\Eloquent\Builder|\App\Models\Donation $q */
                $q->where('complete', 1)
                    ->where('garden_id', $garden->id);
            })
            ->whereNotNull('file_path')
            ->with('donation', 'donation.user', 'donation.garden')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * @return \App\Models\Flower[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll() : Collection
    {
        return Flower::query()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * @return \App\Models\Flower[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllUnoptimized() : Collection
    {
        return Flower::query()
            ->where('optimized', 0)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * @param array $ids
     * @return \App\Models\Flower[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids) : Collection
    {
        return Flower::query()
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * @param array $ids
     * @return \App\Models\Flower[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUnoptimizedByIds(array $ids) : Collection
    {
        return Flower::query()
            ->where('optimized', 0)
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * @param \App\Models\Donation $donation
     * @param int|null $limit
     * @return Flower[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getFromDonation(Donation $donation, int $limit = null) : Collection
    {
        $query = $donation->flowers()
            ->orderBy('created_at', 'ASC');
        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * @param \App\Models\Donation $donation
     * @return \App\Models\Flower|null
     */
    public function getLatestFromDonation(Donation $donation) : ?Flower
    {
        /* @var \App\Models\Flower|null $flower */
        $flower = $donation->flowers()
            ->latest('created_at')
            ->first();

        return $flower;
    }

    /**
     * @param \App\Models\Flower $flower
     * @param bool $value
     */
    public function setOptimized(Flower &$flower, bool $value = true) : void
    {
        $flower->update([
            'optimized' => $value
        ]);
    }

    /**
     * @param \App\Models\Flower $flower
     */
    public function delete(Flower $flower) : void
    {
        try {
            $flower->delete();
        } catch (Exception $e) {
        }
    }
}
