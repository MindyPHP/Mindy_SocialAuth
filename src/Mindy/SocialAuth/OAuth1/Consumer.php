<?php

namespace Mindy\SocialAuth\OAuth1;

use Exception;

class Consumer
{
    /**
     * @var string consumer id
     */
    public $client_id;
    /**
     * @var string consumer secret
     */
    public $secret;
    /**
     * @var
     */
    public $redirect_url;
    /**
     * @var  string  scope separator, most use "," but some like Google are spaces
     */
    public $scope_seperator = ',';
    /**
     * @var  string  consumer key
     */
    protected $key;
    /**
     * @var  string  callback URL for OAuth authorization completion
     */
    protected $callback;
    /**
     * @var  string  scope for OAuth authorization completion
     */
    public $scope;

    /**
     * Sets the consumer key and secret.
     * @param array $options consumer options, key and secret are required
     * @throws \Exception
     */
    public function __construct(array $options = null)
    {
        if (empty($options['clientId'])) {
            throw new Exception('Required option not provided: clientId');
        }

        if (empty($options['redirectUri'])) {
            throw new Exception('Required option not provided: redirectUri');
        }

        $this->client_id = $options['clientId'];

        if (isset($options['redirectUri'])) {
            $this->redirect_url = $options['redirectUri'];
        }
        if (isset($options['clientSecret'])) {
            $this->secret = $options['clientSecret'];
        }
        if (isset($options['scope'])) {
            $this->scope = $options['scope'];
        }
    }

    /**
     * Change the consumer callback.
     *
     * @param string new consumer callback
     * @return $this
     */
    public function callback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function scope($scope)
    {
        $this->scope = is_array($scope) ? implode($this->scope_seperator, $scope) : $this->scope;
        return $this;
    }
}
