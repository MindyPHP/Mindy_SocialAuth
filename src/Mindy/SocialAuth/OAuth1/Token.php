<?php

namespace Mindy\SocialAuth\OAuth1;

use Exception;

abstract class Token
{
    /**
     * @var  string  token type name: request, access
     */
    protected $name;
    /**
     * @var  string  token key
     */
    public $access_token;
    /**
     * @var  string  token secret
     */
    public $secret;
    /**
     * @var  string  uid
     */
    protected $uid;

    /**
     * Sets the token and secret values.
     *
     * @param array $options token options
     * @throws \Exception
     * @return \Mindy\SocialAuth\OAuth1\Token
     */
    public function __construct(array $options = null)
    {
        if (!isset($options['access_token'])) {
            throw new Exception('Required option not passed: access_token');
        }

        if (!isset($options['secret'])) {
            throw new Exception('Required option not passed: secret');
        }

        $this->access_token = $options['access_token'];
        $this->secret = $options['secret'];

        // If we have a uid lets use it
        if (isset($options['uid'])) {
            $this->uid = $options['uid'];
        }
    }

    /**
     * Returns the token key.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->access_token;
    }
}
