<?php

namespace Mindy\SocialAuth\OAuth1;

class Response
{
    /**
     * @var array response parameters
     */
    protected $params = array();

    public function __construct($body = null)
    {
        if ($body) {
            $this->params = OAuth::parseParams($body);
        }
    }

    public function param($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }
}
