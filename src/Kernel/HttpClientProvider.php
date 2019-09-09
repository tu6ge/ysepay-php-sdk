<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 15:41
 */
namespace YsepaySdk\Kernel;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpClientProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['http_client'] = function ($app){
            return new Client($app->config->http ?? []);
        };
    }
}