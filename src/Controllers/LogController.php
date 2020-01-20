<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Components\Settings;
use App\Components\Watcher;
use SessionHandler;
use App\Components\helpers\LogHelper;

class LogController extends BaseController
{
	public function fullContent(Request $request, Response $response)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];

		$workerResult = $workerPath = $workerSection = '';
		$workerErrors = ['Inwalid data'];

		if(!isset($_POST['projectName'])|| !isset($_POST['key']))
			return json_encode(['result'=>'', 'error'=>['Empty Data']]);

		$targetSettings = $settings->getSelected($_POST['projectName'], 'projectLogs', $_POST['key']);

		if(!$targetSettings)
			return json_encode(['result'=>'', 'error'=>['Empty Settings']]);

		$logHelper = new LogHelper($targetSettings);

		return json_encode($logHelper->getFullLogContent());
	}

	public function watcher(Request $request, Response $response)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];

		if(!empty($_POST))
		{
			if(isset($_POST['stopWatcher']))
			{
				$settings->clearWatchersettings();
			}
			else
			{
				$watcherData = [];

				foreach($_POST as $key=>$value)
					$watcherData[] = $key;

				$settings->saveWatcherSettings(array_combine($watcherData, $watcherData));
			}
		}

		$allSettings = $settings->getAllSettings();

		return $this->view->render($response, 'watcher.html', $this->params(['data'=>$allSettings, 'watcher'=>$settings->getWatcherSettings()]));
	}

	public function checkErrors(Request $request, Response $response)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];
		$watcherConfig = $settings->getWatcherSettings();

		if($watcherConfig)
		{
			$watchResult = [];
			$hasError = false;

			foreach($watcherConfig as $targetLogHey)
			{
				$entryDetails = explode('__', $targetLogHey);
				$projectName = $entryDetails[0];
				$logKey = $entryDetails[1];

				$targetsettings = $settings->getSelected($projectName, 'projectLogs', $logKey);

				$logHelper = new LogHelper($targetsettings);
				$logHelper->directory = $this->settings['public'];

				$lastErrorData = $logHelper->getLastErrorLogContent();

				if($lastErrorData)
				{
					$hasError = true;

					$watchResult[] = $lastErrorData;
				}
			}

			if($hasError)
			{
				$result = json_encode($watchResult);

				if(!$result)
				{
					echo "<pre>\n";
					print_r($watchResult);
					die();
				}

				return $result;
			}
		}

		return  json_encode([]);
	}

	public function lastError(Request $request, Response $response, $args)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];
		$content = $settings->getCacheContent($args['key']);

		if(!$content)
		{
			$url = $this->router->pathFor('home');

			return $response->withHeader('Location', $url);
		}
		$content = str_replace("\n", "<BR>", $content);

		return $this->view->render($response, 'lastLogError.html', $this->params([
			'content'=>$content,
			'watcher'=>false,
		]));
	}
}
