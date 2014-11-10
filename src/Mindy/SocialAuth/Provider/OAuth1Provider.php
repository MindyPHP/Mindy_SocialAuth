<?php

namespace Mindy\SocialAuth\Provider;

use Exception;
use Mindy\SocialAuth\OAuth1\Consumer;
use Mindy\SocialAuth\OAuth1\Request\Access as AccessRequest;
use Mindy\SocialAuth\OAuth1\Request\Authorize as AuthorizeRequest;
use Mindy\SocialAuth\OAuth1\Request\Resource as ResourceRequest;
use Mindy\SocialAuth\OAuth1\Request\Token as TokenRequest;
use Mindy\SocialAuth\OAuth1\Token;
use Mindy\SocialAuth\OAuth1\Token\Access as AccessToken;
use Mindy\SocialAuth\OAuth1\Token\Request as RequestToken;

/**
 * OAuth Provider
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
abstract class OAuth1Provider extends BaseProvider
{
    /**
     * @var  string  signature type
     */
    public $signature = 'HMAC-SHA1';
    /**
     * @var \Mindy\SocialAuth\OAuth1\Consumer
     */
    public $consumer;
    /**
     * @var null|string uid user key in received data
     */
    public $uid_key = null;


    /**
     * Overloads default class properties from the options.
     *
     * Any of the provider options can be set here:
     *
     * Type      | Option        | Description                                    | Default Value
     * ----------|---------------|------------------------------------------------|-----------------
     * mixed     | signature     | Signature method name or object                | provider default
     *
     * @param array $options provider options
     * @return \Mindy\SocialAuth\Provider\OAuth1Provider
     */
    public function __construct(array $options = array())
    {
        if (isset($options['signature'])) {
            // Set the signature method name or object
            $this->signature = $options['signature'];
        }

        if (!is_object($this->signature)) {
            // Convert the signature name into an object
            $class = str_replace('-', '', $this->signature);
            $class = 'Mindy\SocialAuth\OAuth1\Signature\\' . $class;
            $this->signature = new $class;
        }

        $this->consumer = new Consumer($options);
    }

    abstract public function requestTokenUrl();

    /**
     * Ask for a request token from the OAuth provider.
     * $token = $provider->request_token($consumer);
     *
     * @param Consumer  consumer
     * @param array additional request parameters
     * @return \Mindy\SocialAuth\OAuth1\Token\Request
     * @uses Request_Token
     */
    public function requestToken($redirect_url = null, array $params = null)
    {
        $redirect_url = $redirect_url ? : $this->consumer->redirect_url;
        $scope = is_array($this->consumer->scope) ? implode($this->consumer->scope_seperator, $this->consumer->scope) : $this->consumer->scope;

        // Create a new GET request for a request token with the required parameters
        $request = new TokenRequest('GET', $this->requestTokenUrl(), array(
            'oauth_consumer_key' => $this->consumer->client_id,
            'oauth_callback' => $redirect_url,
            'scope' => $scope
        ));

        if ($params) {
            // Load user parameters
            $request->params($params);
        }

        // Sign the request using only the consumer, no token is available yet
        $request->sign($this->signature, $this->consumer->scope($scope)->callback($redirect_url));

        // Create a response from the request
        $response = $request->execute();

        // Store this token somewhere useful
        return new RequestToken(array(
            'access_token' => $response->param('oauth_token'),
            'secret' => $response->param('oauth_token_secret'),
        ));
    }

    /**
     * @return bool|true|void
     */
    public function process()
    {
        static $key = 'oauth_token';
        if ($this->isCallback() && isset($_SESSION[$key]) && $this->validateCallback(unserialize($_SESSION[$key]))) {
            return true;
        } else {
            $token = $this->requestToken();
            $_SESSION[$key] = serialize($token);
            $this->setToken($token);
            $url = $this->authorize($token);
            $this->redirect($url);
        }
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function isCallback()
    {
        return isset($_REQUEST['oauth_token']);
    }

    public function validateCallback(RequestToken $token)
    {
        if (isset($_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] === $token->access_token) {
            if (!isset($_REQUEST['oauth_verifier'])) {
                throw new Exception('OAuth verifier was not found in request');
            }
            $token->verifier($_REQUEST['oauth_verifier']);
            $this->token = $this->accessToken($token);
            return true;
        } else {
            throw new Exception('Token mismatch');
        }
    }

    public function getUserToken()
    {
        return isset($this->token) ? $this->token : false;
    }

    public function call($method = 'GET', $url, array $params = array())
    {
        // Create a new GET request with the required parameters
        $request = new ResourceRequest($method, $url, array_merge(array(
            'oauth_consumer_key' => $this->consumer->client_id,
            'oauth_token' => $this->token->access_token
        ), $params));

        // Sign the request using the consumer and token
        $request->sign($this->signature, $this->consumer, $this->token);

        return $request->execute();
    }

    /**
     * Get the authorization URL for the request token.
     * Response::redirect($provider->authorize_url($token));
     *
     * @param \Mindy\SocialAuth\OAuth1\Token\Request $token
     * @param array $params additional request parameters
     * @internal param \Mindy\SocialAuth\OAuth1\Token $Token_Request
     * @return string
     */
    public function authorize(RequestToken $token, array $params = [])
    {
        // Create a new GET request for a request token with the required parameters
        $request = new AuthorizeRequest('GET', $this->authorizeUrl(), array(
            'oauth_token' => $token->access_token,
        ));

        if (!empty($params)) {
            // Load user parameters
            $request->params($params);
        }

        return $request->asUrl();
    }

    /**
     * Exchange the request token for an access token.
     * $token = $provider->access_token($consumer, $token);
     *
     * @param \Mindy\SocialAuth\OAuth1\Token\Request $token
     * @param array $params additional request parameters
     * @return \Mindy\SocialAuth\OAuth1\Token\Access
     */
    public function accessToken(RequestToken $token, array $params = null)
    {
        // Create a new GET request for a request token with the required parameters
        $request = new AccessRequest('GET', $this->accessTokenUrl(), array(
            'oauth_consumer_key' => $this->consumer->client_id,
            'oauth_token' => $token->access_token,
            'oauth_verifier' => $token->verifier,
        ));

        if ($params) {
            // Load user parameters
            $request->params($params);
        }
        // Sign the request using only the consumer, no token is available yet
        $request->sign($this->signature, $this->consumer, $token);
        // Create a response from the request
        $response = $request->execute();

        // Store this token somewhere useful
        return new AccessToken(array(
            'access_token' => $response->param('oauth_token'),
            'secret' => $response->param('oauth_token_secret'),
            'uid' => $response->param($this->uid_key) ? $response->param($this->uid_key) : $_REQUEST[$this->uid_key],
        ));
    }
}
