<?php

namespace Mindy\SocialAuth\OAuth1\Request;

use Mindy\SocialAuth\OAuth1\Request;

class Resource extends Request
{
    protected $name = 'resource';

    // http://oauth.net/core/1.0/#rfc.section.7
    protected $required = array(
        'oauth_consumer_key' => true,
        'oauth_token' => true,
        'oauth_signature_method' => true,
        'oauth_signature' => true,
        'oauth_timestamp' => true,
        'oauth_nonce' => true,
        'oauth_version' => true,
    );
}
