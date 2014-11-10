<?php

namespace Mindy\SocialAuth\Provider;

/**
 * Vkontakte OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Lavr Lyndin
 */
class Vkontakte extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'uid',
        'email' => 'email',
        'avatar' => 'photo_big',
        'birthday' => 'bdate'
    ];

    /**
     * @var string
     */
    public $uid_key = 'user_id';
    /**
     * @var string
     */
    protected $method = 'POST';

    public function authorizeUrl()
    {
        return 'http://oauth.vk.com/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://oauth.vk.com/access_token';
    }

    public function fetchUserInfo()
    {
        $response = $this->get("https://api.vk.com/method/users.get", array(
            'uids' => $this->token->uid,
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $this->token->access_token,
        ));

        $user = current($response);
        return $user[0];
    }

    /**
     * Get user name or null if it is not set
     * @return string|null
     */
    public function getName()
    {
        $result = null;
        if (isset($this->userInfo['first_name']) && isset($this->userInfo['last_name'])) {
            $result = $this->userInfo['first_name'] . ' ' . $this->userInfo['last_name'];
        } elseif (isset($this->userInfo['first_name']) && !isset($this->userInfo['last_name'])) {
            $result = $this->userInfo['first_name'];
        } elseif (!isset($this->userInfo['first_name']) && isset($this->userInfo['last_name'])) {
            $result = $this->userInfo['last_name'];
        }
        return $result;
    }

    /**
     * Get user social id or null if it is not set
     * @return string|null
     */
    public function getSocialPage()
    {
        $result = null;
        if (isset($this->userInfo['screen_name'])) {
            $result = 'http://vk.com/' . $this->userInfo['screen_name'];
        }
        return $result;
    }


    /**
     * Get user sex or null if it is not set
     * @return string|null
     */
    public function getSex()
    {
        $result = null;
        if (isset($this->userInfo['sex'])) {
            $result = $this->userInfo['sex'] == 1;
        }
        return $result;
    }
}
