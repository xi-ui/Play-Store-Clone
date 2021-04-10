<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use Modules\Core\Eloquent\Observers\BaseModelObserver;

class AdvertisementObserver extends BaseModelObserver
{
    public function __construct() {

        $model = app(Advertisement::class);
        $homeAds = app(HomeAdsPlacementBlock::class);

        $this->pushNewTableCacheName(
                                array_merge([

                                ], $model->cacheKeyArray(), $homeAds->cacheKeyArray())
                                );

                                
    }

}