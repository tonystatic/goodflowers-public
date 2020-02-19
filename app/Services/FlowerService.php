<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Donation;
use App\Models\Flower;
use App\Repos\FlowerRepo;
use App\Support\Files\Storage;
use App\Support\Flowers\Generator;
use App\Support\Money\Money;
use Illuminate\Support\Collection;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\Optimizers\Svgo;

class FlowerService
{
    /**
     * @param int $quantity
     * @return \App\Support\Money\Money
     */
    public function getPriceOfQuantity(int $quantity) : Money
    {
        $quantity = ($quantity >= 1 ? $quantity : 1);

        $oneFlowerPrice = Money::fromValue((float) config('flowers.price'));

        return $oneFlowerPrice->multiply($quantity);
    }

    /**
     * @param \App\Models\Donation $donation
     * @return \App\Models\Flower[]|\Illuminate\Support\Collection
     */
    public function createFlowers(Donation $donation) : Collection
    {
        /** @var \App\Repos\FlowerRepo $flowerRepo */
        $flowerRepo = app(FlowerRepo::class);

        /** @var \App\Support\Flowers\Generator $flowerGenerator */
        $flowerGenerator = app(Generator::class);

        /** @var \App\Support\Files\Storage $storage */
        $storage = app(Storage::class);

        $flowers = new Collection();
        for ($i = 1; $i <= $donation->flowers_quantity; ++$i) {
            $generatedFlower = $flowerGenerator->generateFlower();

            $flower = $flowerRepo->create(
                $donation,
                $generatedFlower->getColorParam(),
                $this->shapeParamsToString($generatedFlower->getShapeParams())
            );

            $path = $storage->buildPathAndPutContents(
                $generatedFlower->getSvgFileContents(),
                $flower->hash_id,
                'svg',
                'flowers',
                DISK_PUBLIC,
                2
            );
            unset($generatedFlower);

            $flowerRepo->setFilePath($flower, $path);

            $flowers->push($flower);
        }

        return $flowers;
    }

    /**
     * @param \App\Models\Flower $flower
     */
    public function refreshFlowerFile(Flower &$flower) : void
    {
        /** @var \App\Repos\FlowerRepo $flowerRepo */
        $flowerRepo = app(FlowerRepo::class);

        /** @var \App\Support\Flowers\Generator $flowerGenerator */
        $flowerGenerator = app(Generator::class);

        /** @var \App\Support\Files\Storage $storage */
        $storage = app(Storage::class);

        $regeneratedFlower = $flowerGenerator->regenerateFlower(
            (int) $flower->color,
            $this->decodeShapeParamsString($flower->shape)
        );
        if ($flower->file_path !== null && $storage->pathExists($flower->file_path, DISK_PUBLIC)) {
            $storage->putContentsToPath(
                $regeneratedFlower->getSvgFileContents(),
                $flower->file_path,
                DISK_PUBLIC
            );
            $flowerRepo->touch($flower);
        } else {
            $path = $storage->buildPathAndPutContents(
                $regeneratedFlower->getSvgFileContents(),
                $flower->hash_id,
                'svg',
                'flowers',
                DISK_PUBLIC,
                2
            );
            $flowerRepo->setFilePath($flower, $path);
        }
        unset($regeneratedFlower);
    }

    /**
     * @param array $shapeParams
     * @return string
     */
    protected function shapeParamsToString(array $shapeParams) : string
    {
        $shapeString = '';
        foreach ($shapeParams as $shapeParam) {
            if (\is_int($shapeParam)) {
                $shapeString .= \base_convert($shapeParam, 10, Generator::SHAPE_PARAM_MAX + 1);
            }
        }

        return \mb_strtolower($shapeString);
    }

    /**
     * @param string $shapeParamsString
     * @return array
     */
    protected function decodeShapeParamsString(string $shapeParamsString) : array
    {
        $shapeParams = [];
        foreach (mb_split_string($shapeParamsString) as $char) {
            $shapeParams[] = (int) \base_convert($char, Generator::SHAPE_PARAM_MAX + 1, 10);
        }

        return $shapeParams;
    }

    /**
     * @param \App\Models\Flower $flower
     */
    public function optimizeFlowerImage(Flower $flower) : void
    {
        /** @var \App\Repos\FlowerRepo $flowerRepo */
        $flowerRepo = app(FlowerRepo::class);

        $optimizerChain = (new OptimizerChain())
            ->addOptimizer(
                (new Svgo(config('image-optimizer.optimizers.' . Svgo::class)))
            );
        $optimizerChain->optimize($flower->file_full_path);
        $flowerRepo->setOptimized($flower);
    }
}
