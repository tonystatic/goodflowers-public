<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Spatie\RobotsMiddleware\RobotsMiddleware;

class Robots extends RobotsMiddleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function shouldIndex(Request $request) : bool
    {
        return app()->environment() === 'production';
    }
}
