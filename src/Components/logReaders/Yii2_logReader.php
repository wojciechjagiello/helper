<?php

namespace App\Components\logReaders;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Yii2_logReader
 *
 * @author user
 */
class Yii2_logReader  extends BaseLogReader
{
	public function setSettings()
	{
		$this->datePregMath = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) [0-9]{2}:[0-9]{2}:[0-9]{2}$/";
		$this->findStringAsError = ["][error]["];
		$this->wordsAfterWhichTheMessageBegins = [" with message '", "PDOException: ", '][error]['];
		$this->dateLength = 19;
	}
}
