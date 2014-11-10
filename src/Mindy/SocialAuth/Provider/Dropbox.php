<?php

namespace Mindy\SocialAuth\Provider;

use Mindy\SocialAuth\OAuth1\Request\Resource;

class Dropbox extends OAuth1Provider implements OAuth1ProviderInterface
{
    public $name = 'dropbox';

    public function requestTokenUrl()
    {
        return 'https://api.dropbox.com/1/oauth/request_token';
    }

    public function authorizeUrl()
    {
        return 'http://www.dropbox.com/1/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://api.dropbox.com/1/oauth/access_token';
    }

    public function fetchUserInfo()
    {
        // Create a new GET request with the required parameters
        $request = new Resource('GET', 'https://api.dropbox.com/1/account/info', array(
            'oauth_consumer_key' => $this->consumer->client_id,
            'oauth_token' => $this->token->access_token,
        ));

        // Sign the request using the consumer and token
        $request->sign($this->signature, $this->consumer, $this->token);

        $user = json_decode($request->execute());

        // Create a response from the request
        return array(
            'uid' => $this->token->uid,
            'name' => $user->display_name,
            'email' => $user->email,
            'location' => $user->country,
        );
    }
}
