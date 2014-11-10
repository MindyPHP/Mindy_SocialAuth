<?php

namespace Mindy\SocialAuth\Provider;

use Mindy\OAuth2\Exception;

/**
 * Google OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class Google extends OAuth2Provider implements OAuth2ProviderInterface
{
    public $socialFieldsMap = [
        'socialId' => 'id',
        'email' => 'email',
        'name' => 'name',
        'socialPage' => 'link',
        'avatar' => 'picture',
        'sex' => 'gender'
    ];

    /**
     * @var  string  the method to use when requesting tokens
     */
    public $method = 'POST';

    /**
     * @var  string  scope separator, most use "," but some like Google are spaces
     */
    public $scope_seperator = ' ';

    /**
     * @var string The access type (online/offline)
     */
    protected $access_type = 'offline';

    public function authorizeUrl()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    public function accessTokenUrl()
    {
        return 'https://accounts.google.com/o/oauth2/token';
    }

    public function __construct(array $options = array())
    {
        // Now make sure we have the default scope to get user data
        if (empty($options['scope'])) {
            $options['scope'] = array(
                'https://www.googleapis.com/auth/userinfo.profile',
                'https://www.googleapis.com/auth/userinfo.email'
            );
        }

        // Array it if its string
        $options['scope'] = (array)$options['scope'];

        if (isset($options['access_type'])) {
            $this->access_type = $options['access_type'];
        }

        parent::__construct($options);
    }

    public function authorize($options = array())
    {
        $state = md5(uniqid(rand(), true));

        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri,
            'state' => $state,
            'scope' => is_array($this->scope) ? implode($this->scope_seperator, $this->scope) : $this->scope,
            'response_type' => 'code',
            'approval_prompt' => 'force', // - google force-recheck
            'access_type' => $this->access_type
        );

        return array_merge($params, $this->params);
    }

    /*
    * Get access to the API
    *
    * @param    string  The access code
    * @return   object  Success or failure along with the response details
    */
    public function access($code, $options = array())
    {
        if ($code === null) {
            throw new Exception(array('message' => 'Expected Authorization Code from ' . ucfirst($this->name) . ' is missing'));
        }

        return parent::access($code, $options);
    }

    public function fetchUserInfo()
    {
        $user = $this->get('https://www.googleapis.com/oauth2/v1/userinfo', array(
            'access_token' => $this->token->access_token,
        ));

        return $user;
    }

    /**
     * Get user birthday or null if it is not set
     * @return string|null
     */
    public function getBirthday()
    {
        if (isset($this->userInfo['birthday'])) {
            $this->userInfo['birthday'] = str_replace('0000', date('Y'), $this->userInfo['birthday']);
            $result = date('d.m.Y', strtotime($this->userInfo['birthday']));
        } else {
            $result = null;
        }
        return $result;
    }
}
