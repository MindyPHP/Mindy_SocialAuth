<?php

namespace Mindy\SocialAuth\Provider;

class UbuntuOne extends OAuth1Provider implements OAuth1ProviderInterface
{
    /**
     * @var string
     */
    public $name = 'ubuntuone';
    /**
     * @var string
     */
    public $signature = 'PLAINTEXT';

    public function requestTokenUrl()
    {
        return 'https://one.ubuntu.com/oauth/request/';
    }

    public function authorizeUrl()
    {
        return 'https://one.ubuntu.com/oauth/authorize/';
    }

    public function accessTokenUrl()
    {
        return 'https://one.ubuntu.com/oauth/access/';
    }

    public function fetchUserInfo()
    {
        // Get user data
    }
}
