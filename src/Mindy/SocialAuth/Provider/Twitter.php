<?php

namespace Mindy\SocialAuth\Provider;

use Exception;
use Mindy\SocialAuth\OAuth1\Request\Resource;
use Mindy\SocialAuth\OAuth1\Token;
use Mindy\SocialAuth\OAuth1\Token\Access;

class Twitter extends OAuth1Provider implements OAuth1ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'id',
        'name' => 'screen_name',
        'avatar' => 'profile_image_url'
    ];
    /**
     * @var string
     */
    public $name = 'twitter';
    /**
     * @var string
     */
    public $uid_key = 'user_id';

    public function requestTokenUrl()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }

    public function authorizeUrl()
    {
        return 'https://api.twitter.com/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    public function fetchUserInfo()
    {
        if (!$this->token instanceof Access) {
            throw new Exception('Tokens must be an instance of Access');
        }

        // Create a new GET request with the required parameters
        $request = new Resource('GET', 'https://api.twitter.com/1.1/account/verify_credentials.json', array(
            'oauth_consumer_key' => $this->consumer->client_id,
            'oauth_token' => $this->token->access_token,
            'user_id' => $this->token->uid,
        ));
        // Sign the request using the consumer and token
        $request->sign($this->signature, $this->consumer, $this->token);
        $response = $request->execute();

        $user = json_decode($response, true);
        return $user;
    }
}
