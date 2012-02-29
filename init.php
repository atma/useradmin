<?php defined('SYSPATH') or die('No direct access allowed.');
// Uncomment this or define in toyr bootstrap.php
/*
Route::set('auth/provider', 'auth/provider/<provider>', array('provider' => '[a-z]+'))
	->defaults(array(
		'controller' => 'user',
		'action'     => 'provider',
		'provider'       => NULL,
	));

Route::set('auth/provider_return', 'auth/provider_return/<provider>', array('provider' => '[a-z]+'))
	->defaults(array(
		'controller' => 'user',
		'action'     => 'provider_return',
		'provider'       => NULL,
	));
// Fill all required fields that does not fetched from provider, e.g. twitter email
Route::set('auth/provider_complete', 'auth/provider_complete', array('provider' => '[a-z]+'))
	->defaults(array(
		'controller' => 'user',
		'action'     => 'provider_complete',
	));
*/
// Static file serving (CSS, JS, images)
Route::set('css', '<dir>(/<file>)', array('file' => '.+', 'dir' => '(css|img)'))
   ->defaults(array(
		'controller' => 'user',
		'action'     => 'media',
		'file'       => NULL,
		'dir'       => NULL,
	));

