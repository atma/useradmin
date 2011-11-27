<?php defined('SYSPATH') or die('No direct access allowed.');

class Useradmin_Provider_Vkontakte extends Provider_OAuth2 {

	/**
	 * Data storage
	 * @var int
	 */
	private $uid = null;

	private $data = null;

	public function __construct()
	{
		parent::__construct('vkontakte');
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
        
        // @link http://vkontakte.ru/developers.php?oid=-1&p=%D0%92%D1%8B%D0%BF%D0%BE%D0%BB%D0%BD%D0%B5%D0%BD%D0%B8%D0%B5_%D0%B7%D0%B0%D0%BF%D1%80%D0%BE%D1%81%D0%BE%D0%B2_%D0%BA_API
        // create access token
        $request_response = Request::factory($this->provider->url_access_token(). URL::query(array(
            'client_id' => $config['id'],
            'client_secret' => $config['secret'],
            'code' => $code,
            ))
        )
        ->execute();
        if ($request_response->status() != 200)
            return false;
        $response = json_decode($request_response->body(), true);
        if (isset($response['error']) OR !isset($response['access_token']))
            return false;
        
        $this->uid = $response['user_id'];
        // Trying to fetch additional user data
        $request_response = Request::factory('https://api.vkontakte.ru/method/getProfiles'. URL::query(array(
            'uid' => $this->uid,
            'access_token' => $response['access_token'],
            'fields' => 'nickname,screen_name,photo,photo_big'
            ))
        )
        ->execute();
        if ($request_response->status() != 200)
            return false;
        $data = json_decode($request_response->body(), true);
        // strange vk response behavoiur
        $data = isset($data['response']) ? $data['response'][0] : $data;

		if (!isset($data['error']))
		{
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
		if (isset($this->data['email']))
		{
			return $this->data['email'];
		}
		return '';
	}

	/**
	 * Get the full name (firstname surname) from the provider.
	 * @return string
	 */
	public function name()
	{
		if (isset($this->data['first_name']) AND isset($this->data['last_name']))
		{
			return $this->data['first_name'] .' '. $this->data['last_name'];
		}
		elseif (isset($this->data['screen_name']))
        {
            return $this->data['screen_name'];
        }
		return '';
	}
}
