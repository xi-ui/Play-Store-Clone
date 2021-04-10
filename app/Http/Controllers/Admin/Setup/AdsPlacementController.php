<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use App\Http\Controllers\Admin\Setup\Traits\NavigationTrait;
use App\App\Eloquent\Repositories\AdvertisementRepositoryEloquent;
use App\App\Eloquent\Repositories\HomeAdsPlacementBlockRepositoryEloquent;

class AdsPlacementController extends BaseController
{
    use NavigationTrait, ResponseTrait;

    public function __construct(AdvertisementRepositoryEloquent $adsModel,
                HomeAdsPlacementBlockRepositoryEloquent $homeAdsPlacementModel)
    {
        $this->adsModel              = $adsModel;
        $this->homeAdsPlacementModel = $homeAdsPlacementModel;
    }

    public function getIndex()
    {
        $data = [

            'navigations'               => $this->getNavigations(),
            'ads_collections'           => $this->adsModel->adsCollections(),
            'ads_placement_collections' => $this->homeAdsPlacementModel->adsPlacementWithChildrenCollections()->toArray(),
        ];
        return view('admin.setup.ads-placement', $data);
    }


    public function store(Request $request)
    {
        try {

            $input = $request->all();
            $input = array_first($input);

            $adsPlacementCollections = $this->homeAdsPlacementModel->get();
            foreach($input as $item) {

                $parentPlacementBlockModel = $adsPlacementCollections->where('id',$item['id'])->first();

                if ( $parentPlacementBlockModel) {
                    $childrenArr = array_first($item['children'] ?? []);

                    $childrenIds = array_pluck($childrenArr, 'id');
                     $parentPlacementBlockModel->ads()->sync($childrenIds);
                }
            }
            exit;
            return $this->success('Successfully created / updated.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }

        $node = $this->homePageFooter->makeModel()->create([


        ]);

    }
}
