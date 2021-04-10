<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class HomeAdsPlacementBlockable.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class HomeAdsPlacementBlockable extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

}
