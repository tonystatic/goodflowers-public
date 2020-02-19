<?php

declare(strict_types=1);

namespace App\Http\Controllers\Service;

use Cache;
use Robots;

class MainController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function robots()
    {
        // Cached for 3 hours
        $contents = (string) Cache::remember('robots', 180, function () {
            if (app()->environment() === 'production') {
                Robots::addUserAgent('*');
                Robots::addDisallow([
                    '/service'
                ]);
            } else {
                Robots::addDisallow('*');
            }

            return Robots::generate();
        });

        return response($contents, 200, ['Content-Type' => 'text/plain']);
    }
}
