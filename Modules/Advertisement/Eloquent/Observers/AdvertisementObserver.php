<?php

namespace Modules\Advertisement\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use Modules\Core\Eloquent\Observers\BaseModelObserver;
use Modules\Advertisement\Eloquent\Entities\Advertisement as ModuleAdvertisement;

class AdvertisementObserver extends BaseModelObserver
{
    public function __construct() {

        $model = app(Advertisement::class);
        $moduleAds = app(ModuleAdvertisement::class);
        $homeAds = app(HomeAdsPlacementBlock::class);

        $this->pushNewTableCacheName(
                                array_merge([

                                ], $model->cacheKeyArray(), 
                                    $moduleAds->cacheKeyArray(),
                                    $homeAds->cacheKeyArray()
                                )
                                );
    }

}