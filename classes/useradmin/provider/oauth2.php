<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Oauth 2.0 using Kohana's bundled OAuth module.
 *
 * Kohana's bundled OAuth module supports Vkontakte as provider.
 * 
 */
abstract class Useradmin_Provider_OAuth2 extends Provider {

    protected $oauth;
	/**
	 * Privately used for OAuth requests
	 * @var OAuth_Provider
	 */
	protected $provider;

	protected $provider_name;

	/**
	 * Privately used for OAuth requests
	 * @var OAuth_Consumer
	 */
	protected $consumer;

	public function __construct($provider)
	{
        $this->oauth = new OAuth2;
		$this->provider_name = $provider;
		// Load the configuration for this provider
		$config = Kohana::$config->load('oauth.' . $this->provider_name);
		// Create an consumer from the config
		$this->consumer = OAuth2_Client::factory($config);
		// Load the provider
        $this->provider = $this->oauth->provider($this->provider_name);
	}

	/**
	 * Get the URL to redirect to.
	 * @return string
	 */
	public function redirect_url($return_url)
	{
		// Add the callback URL to the consumer
		$this->consumer->callback(URL::site($return_url, true));
		
		return $this->provider->authorize_url($this->consumer);
	}
}