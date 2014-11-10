<?php

namespace Mindy\SocialAuth\Provider;

/**
 * Mailru OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Lavr Lyndin
 */
class Mailru extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'uid',
        'email' => 'email',
        'name' => 'nick',
        'socialPage' => 'link',
        'avatar' => 'pic_big',
        'birthday' => 'birthday'
    ];

    public $method = 'POST';

    public function authorizeUrl()
    {
        return 'https://connect.mail.ru/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'https://connect.mail.ru/oauth/token';
    }

    private function sigParams(array $params, $secretKey)
    {
        ksort($params);
        $raw = "";
        foreach ($params as $key => $value) {
            $raw .= $key . '=' . $value;
        }
        return md5($this->token->uid . $raw . $secretKey);
    }

    public function fetchUserInfo()
    {
        $params = "app_id={$this->client_id}method=users.getInfosession_key={$this->token->access_token}uids={$this->token->uid}";
        $sig = md5($this->token->uid . $params . $this->client_secret);
        $user = $this->post('http://www.appsmail.ru/platform/api', array(
            'method' => 'users.getInfo',
            'app_id' => $this->client_id,
            'session_key' => $this->token->access_token,
            'uids' => $this->token->uid,
            'sig' => $sig,
        ));
        $user = current($user);
        return $user;
    }

    public function authorize($options = array())
    {
        return array(
            'client_id' => $this->client_id,
            'redirect_uri' => isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri,
            'response_type' => 'code',
        );
    }
}
