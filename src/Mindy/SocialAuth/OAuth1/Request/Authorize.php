<?php

namespace Mindy\SocialAuth\OAuth1\Request;

use Mindy\SocialAuth\OAuth1\Request;

class Authorize extends Request
{
    protected $name = 'request';

    // http://oauth.net/core/1.0/#rfc.section.6.2.1
    protected $required = array(
        'oauth_token' => true,
    );

    public function execute(array $options = null)
    {
        $this->redirect($this->asUrl());
    }
}
