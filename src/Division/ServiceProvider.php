<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Division;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['division'] = function ($app){
            return new Client($app);
        };
    }
}