<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components;

/**
 * Description of HtmlHelper
 *
 * @author user
 */
class HtmlHelper
{
	public static $alertTypes = [
		'error'=>'red',
		'danger'=>'red',
		'success'=>'green',
		'info'=>'light-blue',
		'warning'=>'orange'
	];

	public static $alertIcons = [
		'error'=>'error',
		'danger'=>'error',
		'success'=>'error',
		'info'=>'error',
		'warning'=>'error'
	];

	public static function alert($type, $message)
	{
		echo '
		<div class="card-alert card '. self::$alertTypes[$type].'">
			<div class="card-content white-text">
				<p><i class="mdi-alert-warning"></i><i class="material-icons">'.self::$alertIcons[$type].'</i> '.$message.'</p>
			</div>
			<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
		</div>';
	}
}
