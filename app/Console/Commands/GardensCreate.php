<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repos\GardenRepo;
use Illuminate\Console\Command;

class GardensCreate extends Command
{
    protected $signature = 'gardens:create';

    protected $description = 'Create new garden';

    public function handle() : void
    {
        /** @var \App\Repos\GardenRepo $gardenRepo */
        $gardenRepo = app(GardenRepo::class);

        do {
            $slug = $this->ask('Задайте уникальный идентификатор сада');
            if ($slug !== null) {
                if ($gardenRepo->existsBySlug($slug)) {
                    $this->warn('Этот идентификатор уже занят.');
                    $slug = null;
                }
            }
        } while ($slug === null);

        do {
            $ownerName = $this->ask('Укажите имя владельца');
        } while ($ownerName === null);

        do {
            $ownerLink = $this->ask('Укажите ссылку на профиль владельца');
        } while ($ownerLink === null);

        $gardenRepo->createRaw([
            'slug'       => $slug,
            'owner_name' => $ownerName,
            'owner_link' => $ownerLink
        ]);

        $this->info('Сад успешно создан.');
    }
}
