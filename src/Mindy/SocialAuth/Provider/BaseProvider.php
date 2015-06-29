<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 09/11/14.11.2014 18:12
 */

namespace Mindy\SocialAuth\Provider;

use Exception;

abstract class BaseProvider
{
    /**
     * @var string  provider name
     */
    public $name;
    /**
     * @var string  scope separator, most use "," but some like Google are spaces
     */
    public $scope_seperator = ',';
    /**
     * Social Fields Map for universal keys
     * @var array
     */
    public $socialFieldsMap = [];
    /**
     * Storage for user info
     * @var array
     */
    protected $userInfo = [];
    /**
     * @var string default scope (useful if a scope is required for user info)
     */
    protected $scope;
    /**
     * @var
     */
    protected $token;
    /**
     * @var
     */
    protected $client_id;
    /**
     * @var
     */
    protected $client_secret;
    /**
     * @var  array additional request parameters to be used for remote requests
     */
    protected $params = array();
    /**
     * @var string redirect uri
     */
    protected $redirectUri;

    public function __construct(array $options = array())
    {

    }

    /**
     * Get the authorize URL where the user will be redirected to approve
     * the application.
     * @return string The authorize url
     */
    abstract public function authorizeUrl();

    /**
     * Get the access token URL
     * @return string The access token url
     */
    abstract public function accessTokenUrl();

    /**
     * Get information about the logged in user
     * @return array The user data
     */
    abstract public function fetchUserInfo();

    /**
     * @return true if authenticated else redirect to authentication page
     */
    abstract public function process();

    public function getUserTokens()
    {
        return isset($this->token) ? $this->token : false;
    }

    public function setUserTokens($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Make post request and return result
     *
     * @param string $url
     * @param array|string $params
     * @param bool $parse
     * @return array|string
     */
    public function post($url, $params = [], $parse = true)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        if (!empty($params)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        }
        curl_setopt($curl, CURLOPT_USERAGENT, 'php');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        if ($parse) {
            $result = json_decode($result, true);
        }
        return $result;
    }

    /**
     * Make get request and return result
     *
     * @param $url
     * @param $params
     * @param bool $parse
     * @return mixed
     */
    public function get($url, $params = [], $parse = true)
    {
        $curl = curl_init();
        if (!empty($params)) {
            curl_setopt($curl, CURLOPT_URL, $url . '?' . urldecode(http_build_query($params)));
        } else {
            curl_setopt($curl, CURLOPT_URL, $url);
        }
        curl_setopt($curl, CURLOPT_USERAGENT, 'php');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        if ($parse) {
            $result = json_decode($result, true);
        }
        return $result;
    }

    /**
     * @throws \Exception
     * @return array
     */
    public function getUserInfo()
    {
        if (empty($this->userInfo)) {
            $userInfo = (array)$this->fetchUserInfo();
            if (!is_array($this->userInfo)) {
                throw new Exception("Method fetchUserInfo must return an array");
            }
            $this->userInfo = (array)$userInfo;
        }
        return $this->userInfo;
    }

    protected function getUserInfoAttribute($name)
    {
        $userInfo = $this->getUserInfo();
        $result = null;
        if (isset($this->socialFieldsMap[$name]) && isset($userInfo[$this->socialFieldsMap[$name]])) {
            $result = $userInfo[$this->socialFieldsMap[$name]];
        } else if (isset($userInfo[$name])) {
            $result = $userInfo[$name];
        }
        return $result;
    }

    /**
     * Get user social id or null if it is not set
     * @return string|null
     */
    public function getSocialId()
    {
        return $this->getUserInfoAttribute('socialId');
    }

    /**
     * Get user email or null if it is not set
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getUserInfoAttribute('email');
    }

    /**
     * Get user name or null if it is not set
     * @return string|null
     */
    public function getName()
    {
        return $this->getUserInfoAttribute('name');
    }

    /**
     * Get user social page url or null if it is not set
     * @return string|null
     */
    public function getSocialPage()
    {
        return $this->getUserInfoAttribute('socialPage');
    }

    /**
     * Get url of user's avatar or null if it is not set
     * @return string|null
     */
    public function getAvatar()
    {
        return $this->getUserInfoAttribute('avatar');
    }

    /**
     * Get user sex or null if it is not set
     * @return string|null
     */
    public function getSex()
    {
        return $this->getUserInfoAttribute('sex') == 1;
    }

    /**
     * Get user birthday in format dd.mm.YYYY or null if it is not set
     * @return string|null
     */
    public function getBirthday()
    {
        $value = $this->getUserInfoAttribute('birthday');
        return $value ? date('d.m.Y', strtotime($value)) : $value;
    }

    public function redirect($url)
    {
        header("Location: " . $url);
        die();
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }
}
