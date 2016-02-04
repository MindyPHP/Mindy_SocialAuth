<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 07/11/14.11.2014 19:12
 */

namespace Mindy\SocialAuth;

use Closure;
use Exception;
use Mindy\Helper\Console;
use ReflectionClass;

class SocialAuth
{
    /**
     * @var array
     */
    public $providers = [];
    /**
     * @var array
     */
    private $_providers = [];

    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        foreach ($this->providers as $name => $params) {
            if (isset($params['redirectUri'])) {
                $redirectUri = $params['redirectUri'];
                if ($redirectUri instanceof Closure) {
                    $redirectUri = $redirectUri->__invoke();
                }
                $params['redirectUri'] = $this->absoluteUrl($redirectUri);
            } else {
                throw new Exception("Missing redirectUri for " . $name);
            }
            if (!isset($params['class'])) {
                throw new Exception("Missing class name in " . $name . "provider");
            }
            $reflectClass = new ReflectionClass($params['class']);
            $this->_providers[$name] = $reflectClass->newInstanceArgs([$params]);
        }
    }

    protected function absoluteUrl($url)
    {
        if (Console::isCli()) {
            return $url;
        }

        if (strpos($url, 'http') === 0) {
            return $url;
        } else {
            $isSecure = false;
            if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
                $isSecure = true;
            }
            $host = $_SERVER['HTTP_HOST'];
            return ($isSecure ? 'https://' : 'http://') . $host . $url;
        }
    }

    /**
     * @param $name
     * @return \Mindy\SocialAuth\Provider\OAuth1Provider|\Mindy\SocialAuth\Provider\OAuth2Provider
     */
    public function getProvider($name)
    {
        return isset($this->_providers[$name]) ? $this->_providers[$name] : null;
    }

    /**
     * @return \Mindy\SocialAuth\Provider\OAuth1Provider[]|\Mindy\SocialAuth\Provider\OAuth2Provider[]
     */
    public function getProviders()
    {
        return $this->_providers;
    }

    public function authenticate($name)
    {
        if ($name === null) {
            throw new Exception("Unknown provider");
        }

        return $this->getProvider($name)->process();
    }
}
