<?php

namespace Mindy\SocialAuth\Provider;

interface OAuth2ProviderInterface
{
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