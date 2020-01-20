<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components\logReaders;

/**
 * Description of WatcherYiiXAMPP
 *
 * @author user
 */
class XAMPP_logReader extends BaseLogReader
{

	public function setSettings()
	{
		if(substr($this->path, -16) == '_mysql_error.log')
		{
			$this->datePregMath = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) [0-9 ]{2}:[0-9 ]{2}:[0-9 ]{2}$/";
			$this->findStringAsError = [" [ERROR] "];
			$this->wordsAfterWhichTheMessageBegins = [" [ERROR] "];
			$this->dateLength = 19;
		}
		else
		{
			$this->datePregMath = "/^[[a-zA-Z]{4} [a-zA-Z]{3} [0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{6} [0-9]{4}$/";
			$this->findStringAsError = [" [cgi:error] ", " [:error] "];
			$this->wordsAfterWhichTheMessageBegins = ["PHP Warning:  ","PHP Notice:  ", " [cgi:error] ", " [:error] "];
			$this->dateLength = 32;

		}
	}
}
