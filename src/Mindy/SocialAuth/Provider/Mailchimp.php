<?php

namespace Mindy\SocialAuth\Provider;

/**
 * Mailchimp OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class Mailchimp extends OAuth2Provider implements OAuth2ProviderInterface
{
    /**
     * @var  string  the method to use when requesting tokens
     */
    protected $method = 'POST';

    public function authorizeUrl()
    {
        return 'https://login.mailchimp.com/oauth2/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://login.mailchimp.com/oauth2/token';
    }

    public function fetchUserInfo()
    {
        // Create a response from the request
        return array(
            'uid' => $this->token->access_token,
        );
    }
}
