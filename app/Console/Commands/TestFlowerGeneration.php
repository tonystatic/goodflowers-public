<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Flowers\Generator;
use Illuminate\Console\Command;

class TestFlowerGeneration extends Command
{
    protected $signature = 'test:flower-generation';

    protected $description = 'Test flower generation';

    public function handle() : void
    {
        /** @var \App\Support\Flowers\Generator $generator */
        $generator = app(Generator::class);

        $flower = $generator->generateFlower();

        $this->info('Параметр цвета: ' . $flower->getColorParam() . '.');
        $this->info('Параметры формы: ' . \implode(', ', $flower->getShapeParams()) . '.');
        $this->info('Файл цветка:');
        $this->info($flower->getSvgFileContents());
    }
}
