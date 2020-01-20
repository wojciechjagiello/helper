<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components\helpers;

use App\Components\logReaders\Yii2_logReader;
use App\Components\logReaders\Yii1_logReader;
use App\Components\logReaders\XAMPP_logReader;
use App\Components\Settings;

/**
 * Description of LogHelper
 *
 * @author user
 */
class LogHelper extends BaseHelper
{
	private $tempDirectory;
	private $cacheDirectory;

	public function getFullLogContent()
	{
		if(!file_exists($this->filePath))
			return['error'=>'', 'result'=>'File not exist'];

		$logContent = $this->getLogContent();

		return['error'=>'', 'result'=>$this->changeLogLinesToHTMLString($logContent)];
	}

	public function getLastErrorLogContent()
	{
		if(!file_exists($this->directory.'/logFiles')) @mkdir($this->directory.'/logFiles', 0777, true);
		if(!file_exists($this->directory.'/watcherCache')) @mkdir($this->directory.'/watcherCache', 0777, true);

		$this->tempDirectory = $this->directory.'/logFiles';
		$this->cacheDirectory = $this->directory.'/watcherCache';

		if(!file_exists($this->filePath)) return false;

		$logFileHasBeenModified = false;

		$localLogPath = $this->tempDirectory.'/'.$this->projectName.'__'.$this->logName.'__'.basename($this->filePath);

		if(file_exists($localLogPath))
		{
			if(filesize($localLogPath) != filesize($this->filePath))
			{
				copy($this->filePath, $localLogPath);
				$logFileHasBeenModified = true;
			}
		}
		else
		{
			copy($this->filePath, $localLogPath);
			$logFileHasBeenModified = true;
		}

		if($logFileHasBeenModified)
			return $this->runSpecyficLogReader($localLogPath);

		return false;
	}

	private function getLogContent($length = 1000)
	{
		$lines = [];
		$fp = fopen($this->filePath, "r");

		while(!feof($fp))
		{
		   $line = fgets($fp, 4096);
		   array_push($lines, $line);

		   if(count($lines)>$length)
			   array_shift($lines);
		}

		fclose($fp);

		return $lines;
	}

	private function changeLogLinesToHTMLString($logContent)
	{
		if(!$logContent) return '';

		krsort($logContent);

		foreach($logContent as $log)
			$transformContent = implode("<BR>", $logContent);

			$transformContent = iconv('WINDOWS-1250', 'UTF-8', $transformContent);
		return $transformContent;
	}

	private function runSpecyficLogReader($localLogPath)
	{
		switch($this->projectType)
		{
			case Settings::PROJECT_TYPE_YII2:
				$projectTypeWatcher = new Yii2_logReader();
			break;

			case Settings::PROJECT_TYPE_YII1:
				$projectTypeWatcher = new Yii1_logReader();
			break;

			case Settings::PROJECT_TYPE_XAMPP:
				$projectTypeWatcher = new XAMPP_logReader();
			break;

			default:
				return false;
		}

		$projectTypeWatcher->setLogPath($localLogPath);
		$projectTypeWatcher->setSettings();
		$projectTypeWatcher->processLastError();

		$lastProcessedErrorDate = $this->getLastProcessErrorDate();

		if(!$lastProcessedErrorDate || ($lastProcessedErrorDate != $projectTypeWatcher->getLastErrorDate()))
		{
			$fileNameWithLastError = $this->cacheDirectory.'/'.$this->projectName.'__'.$this->logName.'__'.time().'.txt';

			$this->saveLastProcessErrorDate($projectTypeWatcher->getLastErrorDate());
			$this->saveFullContentOfLog($fileNameWithLastError, $projectTypeWatcher->getFullErrorData());

			$logContent = $projectTypeWatcher->getLastMicroErrorData();

			if(!$logContent) return;

			return [
				'projectName'=>$this->projectName,
				'logName'=>$this->logName,
				'content'=>$logContent,
				'cacheFileName'=>$this->projectName.'__'.$this->logName.'__'.time(),
			];
		}

		return false;
	}


	private function getLastProcessErrorDate()
	{
		$key = $this->projectName.'_'.$this->logName;
		$data = $this->getWatcherDate();

		if(!$data) return false;

		foreach($data as $dataKey=>$dataValue)
			if($dataKey == $key) return $dataValue;

		return false;
	}

	private function saveLastProcessErrorDate( $val)
	{
		$key = $this->projectName.'_'.$this->logName;

		$data = $this->getWatcherDate();
		$data[$key] = $val;
		$this->saveWatcherDate($data);
	}

	private function getWatcherDate()
	{
		if(!file_exists($this->directory.'/watcherDate.json')) return [];

		$content = file_get_contents($this->directory.'/watcherDate.json');

		return json_decode($content, true);
	}

	private function saveWatcherDate($data)
	{
		$fileHandle = fopen($this->directory.'/watcherDate.json' , 'w');

		fwrite($fileHandle, json_encode($data));
		fclose($fileHandle);
	}

	private function saveFullContentOfLog($cacheFileName, $data)
	{
		$fp = fopen($cacheFileName, 'w');

		foreach($data as $entry)
			fwrite($fp, $entry);

		fclose($fp);
	}
}
