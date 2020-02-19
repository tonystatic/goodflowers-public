<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Seo\Contracts\MetaProvider;
use App\Support\Seo\MetaContainer;

abstract class BaseController extends Controller
{
    /** @var \App\Models\User|null */
    protected $user = null;

    /**
     * @param array $with
     * @return User|null
     */
    protected function getUser(array $with = []) : ?User
    {
        if ($this->user === null) {
            /* @var User|null $user */
            $user = auth(GUARD_FRONT)->user();
            $this->user = $user;
        }
        if ($this->user !== null && \count($with) > 0) {
            $this->user = $this->user->load($with);
        }

        return $this->user;
    }

    /**
     * @param \App\Support\Seo\Contracts\MetaProvider $metaProvider
     */
    public function setMetaFromProvider(MetaProvider $metaProvider) : void
    {
        /** @var \App\Support\Seo\MetaContainer $metaContainer */
        $metaContainer = app(MetaContainer::class);
        $metaContainer->setMeta($metaProvider->getMeta());
    }
}
