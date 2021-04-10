<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use Modules\Core\Eloquent\Observers\BaseModelObserver;

class HomeAdsPlacementBlockObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(HomeAdsPlacementBlock::class);
        $ads = app(Advertisement::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray(), $ads->cacheKeyArray())
                                );
    }
}
