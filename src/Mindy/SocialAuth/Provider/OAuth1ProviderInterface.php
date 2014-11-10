<?php

namespace Mindy\SocialAuth\Provider;

interface OAuth1ProviderInterface
{
    /**
     * @return mixed
     */
    public function requestTokenUrl();

    /**
     * @return mixed
     */
    public function authorizeUrl();

    /**
     * @return mixed
     */
    public function accessTokenUrl();

    /**
     * @return mixed
     */
    public function fetchUserInfo();
}