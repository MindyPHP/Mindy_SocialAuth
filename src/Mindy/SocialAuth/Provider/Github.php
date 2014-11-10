<?php

namespace Mindy\SocialAuth\Provider;

/**
 * GitHub OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class Github extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'id',
        'avatar' => 'avatar_url',
        'name' => 'name',
        'socialPage' => 'html_url',
    ];

    public function authorizeUrl()
    {
        return 'https://github.com/login/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://github.com/login/oauth/access_token';
    }

    public function fetchUserInfo()
    {
        $user = $this->get('https://api.github.com/user', array(
            'access_token' => $this->token->access_token,
        ));

        // Create a response from the request
        return $user;
    }
}
