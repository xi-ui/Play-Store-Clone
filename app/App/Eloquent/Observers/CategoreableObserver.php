<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Categoreable;
use Modules\Core\Eloquent\Observers\BaseModelObserver;

class CategoreableObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(Categoreable::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
