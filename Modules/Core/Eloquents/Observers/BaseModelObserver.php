<?php

namespace Modules\Core\Eloquent\Observers;

use Modules\Core\Traits\ModelObserverTrait;

/**
 * Class BaseModelObserver
 * @package namespace Modules\Core\Eloquent\Observers\BaseModelObserver;
 */

abstract class BaseModelObserver
{

    use ModelObserverTrait;

    /*
     *
     */
    public function created($model)
    {
        $this->tableCache($model);
    }

    /*
     *
     */
    public function saved($model)
    {
        $this->tableCache($model);
    }

    /*
     *
     */
    public function deleted($model)
    {
        $this->tableCache($model);
    }

}