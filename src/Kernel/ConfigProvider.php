<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 15:33
 */
namespace YsepaySdk\Kernel;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use YsepaySdk\Client;

class ConfigProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['config'] = function (Client $app){
            return new Config($app->getConfig());
        };
    }
}