<?php

namespace Mindy\SocialAuth\Provider;

use Mindy\OAuth2\Exception;
use Mindy\OAuth2\Token\Access;
use Mindy\OAuth2\Token\Refresh;

// TODO

/**
 * Yandex OAuth2 Provider
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Lavr Lyndin
 */
class Yandex extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $method = 'POST';

    public $socialFieldsMap = [
        'socialId' => 'id',
        'email' => 'default_email',
        'name' => 'real_name',
        'socialPage' => 'link',
        'avatar' => 'picture'
    ];

    public function authorizeUrl()
    {
        return 'https://oauth.yandex.ru/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://oauth.yandex.ru/token';
    }

    public function fetchUserInfo()
    {
        $user = $this->get('https://login.yandex.ru/info', [
            'format' => 'json',
            'oauth_token' => $this->token->access_token
        ]);
        return $user;
    }

    public function access($code, $options = array())
    {
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => isset($options['grant_type']) ? $options['grant_type'] : 'authorization_code',
        );

        switch ($params['grant_type']) {
            case 'authorization_code':
                $params['code'] = $code;
                $params['redirect_uri'] = isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri;
                break;

            case 'refresh_token':
                $params['refresh_token'] = $code;
                break;
        }

        $response = null;
        $url = $this->accessTokenUrl();

        $curl = curl_init($url);

        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8;';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 80);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        curl_close($curl);

        $return = json_decode($response, true);

        if (!empty($return['error'])) {
            throw new Exception($return);
        }

        switch ($params['grant_type']) {
            case 'authorization_code':
                return new Access($return);
                break;

            case 'refresh_token':
                return new Refresh($return);
                break;
        }
    }

}
