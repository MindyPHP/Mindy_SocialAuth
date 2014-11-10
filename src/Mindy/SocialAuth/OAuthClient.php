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
 * @date 10/11/14.11.2014 15:14
 */

namespace Mindy\SocialAuth;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin as GuzzleOAuth;
use InvalidArgumentException;
use Mindy\SocialAuth\OAuth1\Token\Access as OAuth1Token;
use Mindy\SocialAuth\OAuth2\Token\Access as OAuth2Token;

class OAuthClient extends Client
{
    /**
     * @var OAuth1Token|OAuth2Token
     */
    protected $tokens;

    public function __construct($baseUrl = '', $config = null, $tokens = null)
    {
        if ($tokens) {
            $this->setUserTokens($tokens);
        }
        parent::__construct($baseUrl, $config);
    }

    public function setUserTokens($tokens)
    {
        if (!($tokens instanceof OAuth1Token) && !($tokens instanceof OAuth2Token)) {
            throw new InvalidArgumentException('User tokens must be an instance of OAuth\OAuth1\Token\Access or OAuth\OAuth2\Token\Access');
        }

        $this->tokens = $tokens;
        if ($tokens instanceof OAuth1Token) {
            $this->setupOAuth();
        }
    }

    protected function setupOAuth()
    {
        if (isset($this->provider)) {
            $data = array(
                'consumer_key' => $this->provider->consumer->client_id,
                'consumer_secret' => $this->provider->consumer->secret,
                'signature_method' => $this->provider->signature->name
            );
            if (isset($this->tokens)) {
                $data['token'] = $this->tokens->access_token;
                $data['token_secret'] = $this->tokens->secret;
            }
            $oauth = new GuzzleOAuth($data);
            $this->addSubscriber($oauth);
        }
    }

//    public function getBaseUrl($expand = true)
//    {
//        $baseUrl = parent::getBaseUrl(false);
//        $url = $baseUrl;
//        if ($this->tokens instanceof OAuth1Token || $this->tokens instanceof OAuth2Token) {
//            $url .= strpos($url, '?') ? '&' : '?';
//            $url .= http_build_query(array(
//                'access_token' => $this->tokens->access_token
//            ));
//        }
//        $this->setBaseUrl($url);
//        $return = parent::getBaseUrl($expand);
//
//        $this->setBaseUrl($baseUrl);
//        return $return;
//    }

    public function setBaseUrl($url)
    {
        if ($this->tokens) {
            $url .= strpos($url, '?') ? '&' : '?';
            $url .= http_build_query(array(
                'access_token' => $this->tokens->access_token
            ));
        }
        return parent::setBaseUrl($url);
    }
}