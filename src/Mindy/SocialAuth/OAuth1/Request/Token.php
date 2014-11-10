<?php

namespace Mindy\SocialAuth\OAuth1\Request;

use Mindy\SocialAuth\OAuth1\Request;
use Mindy\SocialAuth\OAuth1\Response;

class Token extends Request
{
    protected $name = 'request';

    // http://oauth.net/core/1.0/#rfc.section.6.3.1
    protected $required = array(
        'oauth_callback' => true,
        'oauth_consumer_key' => true,
        'oauth_signature_method' => true,
        'oauth_signature' => true,
        'oauth_timestamp' => true,
        'oauth_nonce' => true,
        'oauth_version' => true,
    );

    public function execute(array $options = null)
    {
        return new Response(parent::execute($options));
    }
}
