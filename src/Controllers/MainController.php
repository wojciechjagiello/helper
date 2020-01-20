<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Components\Settings;
use App\Components\Materialize;
use App\Components\Worker;

class MainController extends BaseController
{
	public function index(Request $request, Response $response)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];
		$allSettings = $settings->getAllSettings();

		return $this->view->render($response, 'index.html', $this->params(['data'=>$allSettings, 'watcher'=>$settings->getWatcherSettings()]));
	}


}
