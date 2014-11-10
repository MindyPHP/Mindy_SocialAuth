<?php

namespace Mindy\SocialAuth\OAuth1\Token;

use Mindy\SocialAuth\OAuth1\Token;

class Request extends Token
{
    /**
     * @var string
     */
    protected $name = 'request';
    /**
     * @var  string  request token verifier
     */
    public $verifier;

    /**
     * Change the token verifier.
     *
     *     $token->verifier($key);
     *
     * @param   string   new verifier
     * @return  $this
     */
    public function verifier($verifier)
    {
        $this->verifier = $verifier;
        return $this;
    }
}
