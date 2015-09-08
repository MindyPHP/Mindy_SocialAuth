<?php

namespace Mindy\SocialAuth\Provider;

/**
 * Facebook OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class Facebook extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'id',
        'email' => 'email',
        'name' => 'name',
        'socialPage' => 'link',
        'sex' => 'gender',
        'birthday' => 'birthday'
    ];

    public $scope = array('offline_access', 'email', 'read_stream');

    public function authorizeUrl()
    {
        return 'https://www.facebook.com/dialog/oauth';
    }

    public function accessTokenUrl()
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }

    public function fetchUserInfo()
    {
        return $this->get('https://graph.facebook.com/me', [
            'access_token' => $this->token->access_token,
            'appsecret_proof' => hash_hmac('sha256', $this->token->access_token, $this->client_secret)
        ]);
    }

    /**
     * Get url of user's avatar or null if it is not set
     * @return string|null
     */
    public function getAvatar()
    {
        $result = null;
        if (isset($this->userInfo['id'])) {
            $result = 'http://graph.facebook.com/' . $this->userInfo['id'] . '/picture?type=large';
        }
        return $result;
    }
}
