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
 * @date 10/11/14.11.2014 13:37
 */

namespace Mindy\SocialAuth\Provider;

use Exception;

class Odnoklassniki extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'uid',
        'avatar' => 'pic_2',
        'sex' => 'gender'
    ];

    /**
     * Social Public Key
     * @var string|null
     */
    public $client_public = null;

    public $method = 'POST';

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        if (empty($options['clientPublic'])) {
            throw new Exception('Required option not provided: clientPublic');
        }

        $this->client_public = $options['clientPublic'];
    }

    /**
     * Get user social id or null if it is not set
     * @return string|null
     */
    public function getSocialPage()
    {
        $result = null;
        if (isset($this->userInfo['uid'])) {
            return 'http://www.odnoklassniki.ru/profile/' . $this->userInfo['uid'];
        }
        return $result;
    }

    public function authorizeUrl()
    {
        return 'http://www.odnoklassniki.ru/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'http://api.odnoklassniki.ru/oauth/token.do';
    }

    public function authorize($options = array())
    {
        return array(
            'client_id' => $this->client_id,
            'redirect_uri' => isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri,
            'response_type' => 'code'
        );
    }

    public function fetchUserInfo()
    {
        $sign = md5("application_key={$this->client_public}format=jsonmethod=users.getCurrentUser" . md5($this->token->access_token . $this->client_secret));
        $params = [
            'method' => 'users.getCurrentUser',
            'access_token' => $this->token->access_token,
            'application_key' => $this->client_public,
            'format' => 'json',
            'sig' => $sign
        ];
        $user = $this->get('http://api.odnoklassniki.ru/fb.do', $params);
        return $user;
    }
}
