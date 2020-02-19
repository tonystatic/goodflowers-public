<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repos\FlowerRepo;
use App\Services\FlowerService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class FlowersOptimize extends Command
{
    protected $signature = 'flowers:optimize
        {ids?* : The IDs of flowers to be optimized}
        {--A|all : Run for already optimized flowers too}
        {--F|force : Force images to be optimized without confirmation}';

    protected $description = 'Optimize flowers\' images by IDs (or all unoptimized)';

    /* @var \App\Repos\FlowerRepo */
    protected $flowerRepo;

    /* @var \App\Services\FlowerService */
    protected $flowerService;

    public function __construct()
    {
        parent::__construct();
        $this->flowerRepo = app(FlowerRepo::class);
        $this->flowerService = app(FlowerService::class);
    }

    public function handle() : void
    {
        $ids = $this->argument('ids');
        $all = (bool) $this->option('all');
        $force = (bool) $this->option('force');

        if (\count($ids) === 0) {
            if ($force || $this->confirm('Вы действительно хотите оптимизировать все цветы?')) {
                $flowers = $all
                    ? $this->flowerRepo->getAll()
                    : $this->flowerRepo->getAllUnoptimized();
                $this->optimizeFlowers($flowers);
            }
        } else {
            $flowers = $all
                ? $this->flowerRepo->getByIds($ids)
                : $this->flowerRepo->getUnoptimizedByIds($ids);

            if ($flowers->count() === 0) {
                $this->info('Цветы с указанными ID не найдены.');
            } elseif (
                $force
                || $this->confirm(
                    'Найдено цветов: '
                    . $flowers->count()
                    . '. Вы действительно хотите оптимизировать эти цветы?'
                )
            ) {
                $this->optimizeFlowers($flowers);
            }
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $flowers
     */
    protected function optimizeFlowers(Collection $flowers) : void
    {
        $bar = $this->output->createProgressBar($flowers->count());
        $bar->start();

        foreach ($flowers as $flower) {
            $this->flowerService->optimizeFlowerImage($flower);
            $bar->advance();
        }

        $bar->finish();
        $this->output->newLine();
        $this->info('Цветы успешно оптимизированы.');
    }
}
