<?php

namespace Mindy\SocialAuth\Provider;

/**
 * Instagram OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class Instagram extends OAuth2Provider implements OAuth2ProviderInterface
{
    /**
     * @var  string  scope separator, most use "," but some like Google are spaces
     */
    public $scope_seperator = '+';

    /**
     * @var  string  the method to use when requesting tokens
     */
    public $method = 'POST';

    public function authorizeUrl()
    {
        return 'https://api.instagram.com/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://api.instagram.com/oauth/access_token';
    }

    public function fetchUserInfo()
    {
        $data = $this->get('https://api.instagram.com/v1/users/self', [
            'access_token' => $this->token->access_token
        ]);
        $user = $data['data'];
        return $user;
    }
}
