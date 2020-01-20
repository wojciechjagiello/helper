<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components;

/**
 * Description of Settings
 *
 * @author user
 */
class Settings
{
	public $directory;
	const PROJECT_TYPE_XAMPP = 'Xampp';
	const PROJECT_TYPE_YII2 = 'Yii2';
	const PROJECT_TYPE_YII1 = 'Yii1';

	public function getAllSettings()
	{
		if(!file_exists($this->directory.'/settings.json')) return [];

		$content = file_get_contents($this->directory.'/settings.json');

		return json_decode($content, true);
	}

	private function saveData($data)
	{
		$fileHandle = fopen($this->directory.'/settings.json' , 'w');

		fwrite($fileHandle, json_encode($data));
		fclose($fileHandle);
	}

	public function getProjectTypes()
	{
		return[
			self::PROJECT_TYPE_XAMPP=>'XAMPP',
			self::PROJECT_TYPE_YII2=>'Yii2',
			self::PROJECT_TYPE_YII1=>'Yii1',
		];
	}

	public function addProject($name, $type)
	{
		$currentData = $this->getAllSettings();

		$currentData[] = [
			'projectName'=>$name,
			'projectType'=>$type,
			'projectLogs'=>[],
			'projectBats'=>[],
		];

		$this->saveData($currentData);
	}

	public function deleteProject($projectName)
	{
		$projects = $this->getAllSettings();

		foreach($projects as $key=>$project)
			if($project['projectName'] == $projectName)
				unset($projects[$key]);

		$this->saveData($projects);
	}

	public function editProject($projectName, $newProjectName, $changeSequence = false)
	{
		$projects = $this->getAllSettings();

		if($changeSequence)
		{
			foreach($projects as $key=>$project)
				if($project['projectName'] == $projectName)
				{
					if($changeSequence == 'up')
						$targetKey = $key-1;
					else
						$targetKey = $key+1;

					if(!isset($projects[$targetKey])) return;

					$tmpData = $projects[$key];
					$projects[$key] = $projects[$targetKey];
					$projects[$targetKey] = $tmpData;
				}
		}
		else
			foreach($projects as $key=>$project)
				if($project['projectName'] == $projectName)
					$projects[$key]['projectName'] = $newProjectName;

		$this->saveData($projects);
	}

	public function addLog($projectName, $name, $path)
	{
		$projects = $this->getAllSettings();

		foreach($projects as $key=>$project)
			if($project['projectName'] == $projectName)
				$projects[$key]['projectLogs'][] = ['name'=>$name, 'path'=>$path];

		$this->saveData($projects);
	}

	public function deleteLog($projectName, $name)
	{
		$projects = $this->getAllSettings();

		foreach($projects as $projectKey=>$project)
			if($project['projectName'] == $projectName)
				foreach($project['projectLogs'] as $logKey=>$projectLog)
					if($projectLog['name'] == $name)
						unset($projects[$projectKey]['projectLogs'][$logKey]);

		$this->saveData($projects);
	}

	public function editLog($projectName, $name, $newName, $newPath, $changeSequence = false)
	{
		$projects = $this->getAllSettings();

		if($changeSequence)
		{
			foreach($projects as $key=>$project)
				if($project['projectName'] == $projectName)
				{
					foreach($project['projectLogs'] as $batKey=>$projectLog)
						if($projectLog['name'] == $name)
						{

							if($changeSequence == 'up')
								$targetKey = $batKey-1;
							else
								$targetKey = $batKey+1;

							if(!isset($projects[$key]['projectLogs'][$targetKey])) return;

							$tmpData = $projects[$key]['projectLogs'][$batKey];
							$projects[$key]['projectLogs'][$batKey] =  $projects[$key]['projectLogs'][$targetKey];
							$projects[$key]['projectLogs'][$targetKey] = $tmpData;
						}
				}
		}
		else
			foreach($projects as $projectKey=>$project)
				if($project['projectName'] == $projectName)
					foreach($project['projectLogs'] as $batKey=>$projectLog)
						if($projectLog['name'] == $name)
						{
							$projects[$projectKey]['projectLogs'][$batKey]['name'] = $newName;
							$projects[$projectKey]['projectLogs'][$batKey]['path'] = $newPath;
						}

		$this->saveData($projects);
	}

	public function addBat($projectName,$name, $path, $changeSequence = false)
	{
		$projects = $this->getAllSettings();

		foreach($projects as $key=>$project)
			if($project['projectName'] == $projectName)
				$projects[$key]['projectBats'][] = ['name'=>$name,'path'=>$path];

		$this->saveData($projects);
	}

	public function deleteBat($projectName, $name)
	{
		$projects = $this->getAllSettings();

		foreach($projects as $projectKey=>$project)
			if($project['projectName'] == $projectName)
				foreach($project['projectBats'] as $batKey=>$projectBat)
					if($projectBat['name'] == $name)
						unset($projects[$projectKey]['projectBats'][$batKey]);

		$this->saveData($projects);
	}

	public function editBat($projectName, $name, $newName, $newPath, $changeSequence = false)
	{
		$projects = $this->getAllSettings();

		if($changeSequence)
		{
			foreach($projects as $key=>$project)
				if($project['projectName'] == $projectName)
				{
					foreach($project['projectBats'] as $batKey=>$projectLog)
						if($projectLog['name'] == $name)
						{
							if($changeSequence == 'up')
								$targetKey = $batKey-1;
							else
								$targetKey = $batKey+1;

							if(!isset($projects[$key]['projectBats'][$targetKey])) return;

							$tmpData = $projects[$key]['projectBats'][$batKey];
							$projects[$key]['projectBats'][$batKey] =  $projects[$key]['projectBats'][$targetKey];
							$projects[$key]['projectBats'][$targetKey] = $tmpData;
						}
				}
		}
		else
			foreach($projects as $projectKey=>$project)
				if($project['projectName'] == $projectName)
					foreach($project['projectBats'] as $batKey=>$projectBat)
						if($projectBat['name'] == $name)
						{
							$projects[$projectKey]['projectBats'][$batKey]['name'] = $newName;
							$projects[$projectKey]['projectBats'][$batKey]['path'] = $newPath;
						}

		$this->saveData($projects);
	}

	public function getDataByDataTarget($target)
	{
		$targetArray = explode('__', $target);
		$projectName = $targetArray[0];
		$type = $targetArray[1] == 'bat'? 'projectBats' : 'projectLogs';
		$name = $targetArray[2];

		$projects = $this->getAllSettings();

		foreach($projects as $project)
			if($project['projectName'] == $projectName)
				foreach($project[$type] as $projectSettings)
					if($projectSettings['name'] == $name)
					{
						$projectSettings['projectName'] = $projectName;
						$projectSettings['section'] = $type;
						return $projectSettings;
					}

		return [];
	}

	public function getSelected($projectName, $type, $name)
	{
		$projects = $this->getAllSettings();

		foreach($projects as $project)
			if($project['projectName'] == $projectName)
				foreach($project[$type] as $settings)
					if($settings['name'] == $name)
					{
						$settings['projectName'] = $projectName;
						$settings['projectType'] = $project['projectType'];
						$settings['section'] = $type;

						return $settings;
					}
		return [];
	}

	public function getCacheContent($key)
	{
		$watcherCacheDir = $this->directory.'/watcherCache';
		$cacheFileName = $watcherCacheDir.'/'.$key.'.txt';

		if(file_exists($cacheFileName))
		{
			return file_get_contents($cacheFileName);
		}

		return false;
	}

	public function vlidateParams($params)
	{
		$errors = [];

		if(isset($params['newProjectName'])) $this->validateName($params['newProjectName'], $errors);
		elseif(isset($params['projectName'])) $this->validateName($params['projectName'], $errors);

		if(isset($params['newName'])) $this->validateName($params['newName'], $errors);
		elseif(isset($params['name'])) $this->validateName($params['name'], $errors);


		if($params['action'] == 'addBat' || $params['action'] == 'editBat')
		{
			if(isset($params['path'])) $this->validatePath($params['path'], $errors);
			if(isset($params['newPath'])) $this->validatePath($params['newPath'], $errors);
		}

		if(!$errors)
			return false;

		return $errors;
	}

	private function validateName($value, &$errors)
	{
		if(trim($value) == "")
			$errors[] = 'Fill in all fields';

		if(!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9\_\-]*$/', $value))
		{
			$errors[] = '{ '.$value.' } - Invalid value use letters, numbers and characters: "_", "-"';
		}
	}

	private function validatePath($value, &$errors)
	{
		if(trim($value) == "")
			$errors[] = 'Fill in all fields';

		if(!file_exists($value))
		{
			$errors[] = 'The .bat file does not exist';
		}
	}

	public function getWatcherSettings()
	{
		if(!file_exists($this->directory.'/watcherSettings.json')) return false;

		$content = file_get_contents($this->directory.'/watcherSettings.json');

		return json_decode($content, true);
	}

	public function saveWatcherSettings($data)
	{
		$fileHandle = fopen($this->directory.'/watcherSettings.json' , 'w');

		fwrite($fileHandle, json_encode($data));
		fclose($fileHandle);

		$this->clearWatcherCache();
	}

	public function clearWatchersettings()
	{
		if(!file_exists($this->directory.'/watcherSettings.json')) return;

		unlink($this->directory.'/watcherSettings.json');
	}

	private function clearWatcherCache()
	{
		$files = glob($this->directory.'/watcherCache/*');

		foreach($files as $file)
			if(is_file($file)) @unlink($file);
	}
}
