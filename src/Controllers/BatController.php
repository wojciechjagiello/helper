<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Components\Settings;
use SessionHandler;
use App\Components\helpers\BatHelper;

class BatController extends BaseController
{
	public function run(Request $request, Response $response)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];

		$workerErrors = ['Inwalid data'];

		if(!isset($_POST['projectName'])|| !isset($_POST['key']))
			return json_encode(['result'=>'', 'error'=>['Empty Data']]);

		$targetSettings = $settings->getSelected($_POST['projectName'], 'projectBats', $_POST['key']);

		if(!$targetSettings)
			return json_encode(['result'=>'', 'error'=>['Empty Settings']]);

		$batHelper = new BatHelper($targetSettings);

		return json_encode($batHelper->runSingle());
	}
}
