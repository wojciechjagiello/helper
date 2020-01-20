<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components\helpers;

/**
 * Description of Helper
 *
 * @author user
 */
class BaseHelper
{
	public $directory;

	protected $projectName;
	protected $projectType;
	protected $logName;
	protected $filePath;

	public function __construct($params)
	{
		$this->projectName = $params['projectName'];
		$this->projectType = $params['projectType'];
		$this->logName = $params['name'];
		$this->filePath = $params['path'];
	}
}
