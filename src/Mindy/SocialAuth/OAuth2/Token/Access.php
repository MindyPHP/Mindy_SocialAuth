<?php

namespace Mindy\SocialAuth\OAuth2\Token;

use Mindy\SocialAuth\OAuth2\Exception;

/**
 * OAuth2 Token
 *
 * @package    OAuth2
 * @category   Token
 * @author     Phil Sturgeon
 * @copyright  (c) 2011 HappyNinjas Ltd
 */
class Access
{
    /**
     * @var  string  access_token
     */
    public $access_token;
    /**
     * @var  int  expires
     */
    public $expires;
    /**
     * @var  string  refresh_token
     */
    public $refresh_token;
    /**
     * @var  string  uid
     */
    public $uid;

    /**
     * Sets the token, expiry, etc values.
     *
     * @param array $options token options
     *
     * @throws Exception if required options are missing
     */
    public function __construct(array $options = null)
    {
        if (!isset($options['access_token'])) {
            throw new Exception('Required option not passed: access_token' . PHP_EOL . print_r($options, true));
        }

        // if ( ! isset($options['expires_in']) and ! isset($options['expires']))
        // {
        //  throw new Exception('We do not know when this access_token will expire');
        // }

        $this->access_token = $options['access_token'];

        // Some providers (not many) give the uid here, so lets take it
        if (isset($options['uid'])) {
            $this->uid = $options['uid'];
        }

        // Vkontakte uses user_id instead of uid
        if (isset($options['user_id'])) {
            $this->uid = $options['user_id'];
        }

        // Mailru uses x_mailru_vid instead of uid
        if (isset($options['x_mailru_vid'])) {
            $this->uid = $options['x_mailru_vid'];
        }

        // We need to know when the token expires, add num. seconds to current time
        if (isset($options['expires_in'])) {
            $this->expires = time() + ((int)$options['expires_in']);
        }

        // Facebook is just being a spec ignoring jerk
        if (isset($options['expires'])) {
            $this->expires = time() + ((int)$options['expires']);
        }

        // Grab a refresh token so we can update access tokens when they expires
        if(isset($options['refresh_token'])) {
            $this->refresh_token = $options['refresh_token'];
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
