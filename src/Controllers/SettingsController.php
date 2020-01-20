<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Components\Settings;
use App\Components\Materialize;

class SettingsController extends BaseController
{
	public function index(Request $request, Response $response)
	{
		$settings = new Settings();
		$settings->directory = $this->settings['public'];

		$allSettings = $settings->getAllSettings();
		$errors = false;

		if(isset($_SESSION['SETTING_ERROR']))
		{
			$errors = $_SESSION['SETTING_ERROR'];

			unset($_SESSION['SETTING_ERROR']);
		}

		return $this->view->render($response, 'settings.html', $this->params([
			'settings'=>$allSettings,
			'projectTypes'=>$settings->getProjectTypes(),
			'errors'=>$errors,
			'watcher'=>$settings->getWatcherSettings()
		]));
	}

	public function save(Request $request, Response $response)
	{
		$action = $_POST['action'];
		$settings = new Settings();
		$settings->directory = $this->settings['public'];
		$url = $this->router->pathFor('settings');

		if(($errors = $settings->vlidateParams($_POST)) !== false)
		{
			$_SESSION['SETTING_ERROR'] = $errors;

			return $response->withHeader('Location', $url);
		}

		switch($action)
		{
			case 'addProject':
				$settings->addProject($_POST['name'], $_POST['type']);
			break;

			case 'delProject':
				$settings->deleteProject($_POST['projectName']);
			break;

			case 'editProject':
				if(isset($_POST['moveDown']))
					$sequence = 'down';
				elseif(isset($_POST['moveUp']))
					$sequence = 'up';
				else
					$sequence = false;

				$settings->editProject($_POST['projectName'], $_POST['newProjectName'], $sequence);
			break;



			case 'addLog':
				$settings->addLog($_POST['projectName'], $_POST['name'], $_POST['path']);
			break;

			case 'delLog':
				$settings->deleteLog($_POST['projectName'], $_POST['name']);
			break;
			case 'editLog':
				if(isset($_POST['moveDown']))
					$sequence = 'down';
				elseif(isset($_POST['moveUp']))
					$sequence = 'up';
				else
					$sequence = false;

				$settings->editLog($_POST['projectName'], $_POST['name'], $_POST['newName'], $_POST['newPath'], $sequence);
			break;



			case 'addBat':
				$settings->addBat($_POST['projectName'], $_POST['name'], $_POST['path']);
			break;

			case 'delBat':
				$settings->deleteBat($_POST['projectName'], $_POST['name']);
			break;

			case 'editBat':
				if(isset($_POST['moveDown']))
					$sequence = 'down';
				elseif(isset($_POST['moveUp']))
					$sequence = 'up';
				else
					$sequence = false;

				$settings->editBat($_POST['projectName'], $_POST['name'], $_POST['newName'], $_POST['newPath'], $sequence);
			break;

			default:
				$_SESSION['SETTING_ERROR'] = ['Invalid query'];
		}

		return $response->withHeader('Location', $url);
	}
}
