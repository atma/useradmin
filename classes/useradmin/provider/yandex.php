<?php defined('SYSPATH') or die('No direct access allowed.');

class Useradmin_Provider_Yandex extends Provider_OAuth2 {

    /**
     * Data storage
     * @var int
     */
    private $uid = null;

    private $data = null;

    public function __construct()
    {
        parent::__construct('yandex');
    }

    /**
     * Verify the login result and do whatever is needed to access the user data from this provider.
     * @return bool
     */
    public function verify()
    {
        $code = Request::current()->query('code');
        if (!$code OR Request::current()->query('error'))
        {
            return false;
        }
        $config = Kohana::$config->load('oauth.' . $this->provider_name);
        $request_headers = array(
            'client_id' => $config['id'],
            'client_secret' => $config['secret'],
            'code' => $code,
            'response_type' => 'code',
            'grant_type' => 'authorization_code'
        );
        // create access token
        $request_response = Request::factory($this->provider->url_access_token())
            ->method(Request::POST)
            ->post($request_headers)
            ->execute();
        if ($request_response->status() != 200)
            return false;
        $response = json_decode($request_response->body(), true);
        if (isset($response['error']) OR !isset($response['access_token']))
            return false;

        //$this->uid = $response['user_id'];
        // Trying to fetch additional user data
        $request_response = Request::factory('https://api-yaru.yandex.ru/me/'. URL::query(array(
                'oauth_token' => $response['access_token']
            ))
        )
        ->execute();
        if (in_array($request_response->status(), array(201, 301, 302, 303, 307)) AND $request_response->headers('Location'))
        {
            $follow_response = Request::factory($request_response->headers('Location').'&format=json')
                ->headers($request_headers)
                ->execute();
            if ($follow_response->status() != 200)
                return false;
            $request_response = $follow_response;
        }
        elseif ($request_response->status() != 200)
            return false;
        $data = json_decode($request_response->body(), true);

        if (!isset($data['error']) AND isset($data['id']))
        {
            if (strpos($data['id'], '/') !== false)
            {
                $uid = explode('/', $data['id']);
                $this->uid = $uid[1];
            }
            else
            {
                $this->uid = $data['id'];
            }
            $this->data = $data;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Attempt to get the provider user ID.
     * @return mixed
     */
    public function user_id()
    {
        return $this->uid;
    }

    /**
     * Attempt to get the email from the provider (e.g. for finding an existing account to associate with).
     * @return string
     */
    public function email()
    {
        return isset($this->data['email']) ? $this->data['email'] : '';
    }

    /**
     * Get the full name (firstname surname) from the provider.
     * @return string
     */
    public function name()
    {
        return isset($this->data['name']) ? $this->data['name'] : '';
    }
}
