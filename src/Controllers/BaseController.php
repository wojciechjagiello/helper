<?php

namespace App\Controllers;

use Slim\Container;
use App\Components\Materialize;
use App\Components\HtmlHelper;

class BaseController
{
	var $container;

	public function __construct(Container $container)
	{
		ini_set('session.gc_maxlifetime', 3600 * 12);
		session_set_cookie_params(3600 * 12);

		session_start();
		$this->container = $container;
	}

	public function __get($var)
	{
		return $this->container->{$var};
	}

	protected function params($params)
	{
		return array_merge(
		[
			'time'=>time(),
			'watcher'=>false,
			'materialize'=>new Materialize,
			'htmlHelper'=>new HtmlHelper,
		], $params);
	}
}