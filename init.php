<?php defined('SYSPATH') or die('No direct access allowed.');

Route::set('user/provider', 'user/provider/<provider>', array('provider' => '[a-z]+'))
	->defaults(array(
		'controller' => 'user',
		'action'     => 'provider',
		'provider'       => NULL,
	));

Route::set('user/provider_return', 'user/provider_return/<provider>', array('provider' => '[a-z]+'))
	->defaults(array(
		'controller' => 'user',
		'action'     => 'provider_return',
		'provider'       => NULL,
	));
// Fill all required fields that does not fetched from provider, e.g. twitter email
Route::set('user/provider_complete', 'user/provider_complete', array('provider' => '[a-z]+'))
	->defaults(array(
		'controller' => 'user',
		'action'     => 'provider_complete',
	));

// Static file serving (CSS, JS, images)
Route::set('css', '<dir>(/<file>)', array('file' => '.+', 'dir' => '(css|img)'))
   ->defaults(array(
		'controller' => 'user',
		'action'     => 'media',
		'file'       => NULL,
		'dir'       => NULL,
	));

