<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 14:27
 */
namespace YsepaySdk;

use Pimple\Container;
use YsepaySdk\BasicService\ServiceProvider;
use YsepaySdk\Kernel\ConfigProvider;
use YsepaySdk\Kernel\HttpClientProvider;
use YsepaySdk\Kernel\LoggerProvider;
use YsepaySdk\Order\ServiceProvider as OrderProvider;
use YsepaySdk\Qrcode\ServiceProvider as QrcodeProvider;
use YsepaySdk\Alipay\ServiceProvider as AlipayProvider;
use YsepaySdk\Wxpay\ServiceProvider as WxpayProvider;
use YsepaySdk\Df\ServiceProvider as DfProvider;
use YsepaySdk\Division\ServiceProvider as DivisionProvider;
use YsepaySdk\Ydt\ServiceProvider as YdtProvider;
use YsepaySdk\Authenticate\ServiceProvider as AuthenticateProvider;

/**
 * Class Client
 * @property \YsepaySdk\BasicService\Client $basic
 * @property \YsepaySdk\Kernel\Config       $config
 * @property \GuzzleHttp\Client             $http_client
 * @property \Monolog\Logger                $logger
 * @property \YsepaySdk\Order\Client        $order
 * @property \YsepaySdk\Qrcode\Client       $qrcode
 * @property \YsepaySdk\Alipay\Client       $alipay
 * @property \YsepaySdk\Wxpay\Client        $wxpay
 * @property \YsepaySdk\Df\Client           $df
 * @property \YsepaySdk\Division\Client     $division
 * @property \YsepaySdk\Ydt\Client          $ydt
 * @property \YsepaySdk\Authenticate\Client $auth
 */
class Client extends Container
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $providers = [];
    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    public function __construct(array $config = [], array $prepends = [])
    {
        $this->registerProviders($this->getProviders());

        parent::__construct($prepends);

        $this->userConfig = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $base = [
            // http://docs.guzzlephp.org/en/stable/request-options.html
            'http' => [
                'timeout' => 30.0,
            ],
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            ServiceProvider::class,
            ConfigProvider::class,
            HttpClientProvider::class,
            LoggerProvider::class,

            OrderProvider::class,
            QrcodeProvider::class,
            AlipayProvider::class,
            WxpayProvider::class,
            DfProvider::class,
            DivisionProvider::class,
            YdtProvider::class,
            AuthenticateProvider::class,
        ], $this->providers);
    }

    /**
     * @param string $id
     * @param mixed  $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}