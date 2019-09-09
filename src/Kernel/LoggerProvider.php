<?php
namespace YsepaySdk\Kernel;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LoggerProvider implements ServiceProviderInterface
{
    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::NOTICE,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alert' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];
    public function register(Container $pimple)
    {
        $pimple['logger'] = function ($app){
            return new Logger($this->parseChannel($app->config->log), [
                new StreamHandler($app->config->log['path'], $this->level($app->config->log))
            ]);
        };
    }

    public function parseChannel($config)
    {
        return $config['name'] ?? 'ysepay-sdk';
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new \InvalidArgumentException('Invalid log level.');
    }
}