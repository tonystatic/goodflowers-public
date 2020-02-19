<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repos\FlowerRepo;
use App\Services\FlowerService;
use Illuminate\Console\Command;

class FlowersRefresh extends Command
{
    protected $signature = 'flowers:refresh
        {ids?* : The IDs of flowers to be refreshed}';

    protected $description = 'Refresh flowers by IDs (or all flowers)';

    public function handle() : void
    {
        /** @var \App\Repos\FlowerRepo $flowerRepo */
        $flowerRepo = app(FlowerRepo::class);

        /** @var \App\Services\FlowerService $flowerService */
        $flowerService = app(FlowerService::class);

        $ids = $this->argument('ids');

        if (\count($ids) === 0) {
            if ($this->confirm('Вы действительно хотите обновить все цветы?')) {
                $flowers = $flowerRepo->getAll();

                $bar = $this->output->createProgressBar(\count($flowers));
                $bar->start();

                foreach ($flowers as $flower) {
                    $flowerService->refreshFlowerFile($flower);
                    $bar->advance();
                }

                $bar->finish();
                $this->output->newLine();
                $this->info('Цветы успешно обновлены.');
            }
        } else {
            $flowers = $flowerRepo->getByIds($ids);

            if (\count($flowers) === 0) {
                $this->info('Цветы с указанными ID не найдены.');
            } elseif (
                $this->confirm(
                    'Найдено цветов: ' . \count($flowers) . '. Вы действительно хотите обновить эти цветы?'
                )
            ) {
                $bar = $this->output->createProgressBar(\count($flowers));
                $bar->start();

                foreach ($flowers as $flower) {
                    $flowerService->refreshFlowerFile($flower);
                    $bar->advance();
                }

                $bar->finish();
                $this->output->newLine();
                $this->info('Цветы успешно обновлены.');
            }
        }
    }
}
