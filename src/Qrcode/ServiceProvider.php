<?php
namespace YsepaySdk\Qrcode;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['qrcode'] = function ($app){
            return new Client($app);
        };
    }
}