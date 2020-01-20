<?php
namespace App\Components\logReaders;
use App\Components\helpers\LogHelper;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseLogReader
 *
 * @author user
 */
class BaseLogReader
{
	protected $datePregMath ;
	protected $findStringAsError;
	protected $wordsAfterWhichTheMessageBegins;
	protected $dateLength;

	protected $path;
	protected $lastErrorDate;
	protected $fullErrorData;
	protected $microErrorData;


	public function getLastErrorDate(){return $this->lastErrorDate;}
	public function getFullErrorData(){return $this->fullErrorData;}

	public function getLastMicroErrorData(){ return $this->microErrorData;}
	public function setLogPath($logPath){$this->path = $logPath;}

	public function processLastError()
	{
		$this->getFullContentOfLastError();
		$lines = $this->fullErrorData;

		foreach($lines as $key=>$line)
		{
			foreach($this->wordsAfterWhichTheMessageBegins as $word)
			{
				if((strpos($line, $word) !== false))
				{
					$errorArray = explode($word, $line);
					$msg  = $errorArray[1];

					$msg = iconv('WINDOWS-1250', 'UTF-8', $msg);

					$this->microErrorData = $msg;

					return;
				}
			}
		}
	}

	private function getFullContentOfLastError()
	{
		$lines = $this->getLogContent($this->path, 1000);

		$content = [];

		foreach($lines as $line)
		{
			if(strlen($line) < 4) continue;

			$pseudoDate = substr($line, 0 , $this->dateLength);

			if(preg_match($this->datePregMath, $pseudoDate))
			{
				foreach($this->findStringAsError as $stringError)
				{

					if((strpos($line, $stringError) !== false))
					{

						$content = [];
						$content[] = $line;
						$this->lastErrorDate = $pseudoDate;
					}
				}
			}
		}

		$this->fullErrorData = $content;
	}

	private function getLogContent($path, $linesLimit = 1000)
	{
		$lines = [];
		$fp = fopen($path, "r");

		while(!feof($fp))
		{
		   $line = fgets($fp, 4096);
		   array_push($lines, $line);

		   if(count($lines)>$linesLimit)
			   array_shift($lines);
		}

		fclose($fp);

		return $lines;
	}
}
