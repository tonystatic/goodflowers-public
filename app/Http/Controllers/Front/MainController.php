<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

class MainController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.index');
    }

    public function privacy()
    {
        return view('front.privacy');
    }

    public function offer()
    {
        return view('front.offer');
    }
}
