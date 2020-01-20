<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components\helpers;

/**
 * Description of batHelper
 *
 * @author user
 */
class BatHelper extends BaseHelper
{
	public function runSingle()
	{
		if(!file_exists($this->filePath))
			return['error'=>['File not exist'], 'result'=>''];

				if(substr(php_uname(), 0, 7) == 'Windows')
		{
			$pipes = array();
			proc_close(proc_open('start /B "Command Title" '.$command.$parameters, array(array('pipe','r')), $pipes));
		}
		else
			exec($command.$parameters.' > /dev/null &');










		$directory = dirname($this->filePath);
chdir($directory);
		$cmd = ' start '.$this->filePath;
		    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r"));
    }
    else {
        exec($cmd . " > /dev/null &");
    }

	//	exec('cd '.$directory.' &&  start '.$this->filePath);

		exec(' cd '.$directory.' &&  start '.$this->filePath.'&> /dev/null &');

		return['error'=>'', 'result'=>'ok'];
	}
}
