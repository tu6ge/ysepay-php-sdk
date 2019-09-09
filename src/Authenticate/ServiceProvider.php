<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 14:55
 */
namespace YsepaySdk\Authenticate;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['auth'] = function ($app){
            return new Client($app);
        };
    }
}