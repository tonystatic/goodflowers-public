<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Transformers\Front\FlowerTransformer;
use App\Meta\FlowerMeta;
use App\Meta\GardenMeta;
use App\Repos\FlowerRepo;
use App\Repos\GardenRepo;
use App\Support\Response\StandardResponse;
use Illuminate\Http\Request;

class GardenController extends BaseController
{
    /**
     * @param string $gardenSlug
     * @param \Illuminate\Http\Request $request
     * @param \App\Repos\GardenRepo $gardenRepo
     * @param \App\Repos\FlowerRepo $flowerRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function garden(string $gardenSlug, Request $request, GardenRepo $gardenRepo, FlowerRepo $flowerRepo)
    {
        $garden = $gardenRepo->findBySlug($gardenSlug);
        if ($garden === null) {
            return standard_response()
                ->warning(false, 'Сад не найден.', route('front.index'));
        }

        $meta = new GardenMeta($garden);

        if ($request->has('flower')) {
            $flower = $flowerRepo->findByHashId($request->input('flower'));
            if ($flower !== null) {
                $meta = new FlowerMeta($flower);
            }
        }

        $this->setMetaFromProvider($meta);

        return view('front.garden', [
            'garden' => $garden
        ]);
    }

    /**
     * @param string $gardenSlug
     * @param \App\Repos\GardenRepo $gardenRepo
     * @param \App\Repos\FlowerRepo $flowerRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function flowersData(string $gardenSlug, GardenRepo $gardenRepo, FlowerRepo $flowerRepo)
    {
        $garden = $gardenRepo->findBySlug($gardenSlug);
        if ($garden === null) {
            return standard_response()
                ->warning(false, 'Сад не найден.', route('front.index'));
        }

        $flowers = $flowerRepo->getAllActiveInGarden($garden);

        return standard_response(StandardResponse::TYPE_JSON)
            ->success(
                null,
                null,
                $this->transformCollection($flowers, new FlowerTransformer($this->getUser()))
            );
    }
}
