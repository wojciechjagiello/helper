<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components\logReaders;

/**
 * Description of WatcherYii1
 *
 * @author user
 */
class Yii1_logReader extends BaseLogReader
{
	public function setSettings()
	{
		$this->datePregMath = "/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1]) [0-9]{2}:[0-9]{2}:[0-9]{2}$/";
		$this->findStringAsError = [" [error] "];
		$this->wordsAfterWhichTheMessageBegins = ["[php]", " [error] "];
		$this->dateLength = 19;
	}
}
