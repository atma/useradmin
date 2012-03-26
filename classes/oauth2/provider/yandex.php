<?php defined('SYSPATH') OR die('No direct access allowed.');

class OAuth2_Provider_Yandex extends OAuth2_Provider {

    public $name = 'yandex';

    public function url_authorize()
    {
        return 'https://oauth.yandex.com/authorize';
    }

    public function url_access_token()
    {
        return 'https://oauth.yandex.com/token';
    }

}
