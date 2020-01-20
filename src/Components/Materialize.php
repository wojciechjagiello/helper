<?php

namespace App\Components;

/**
 * Description of Materialize
 */
class Materialize {
	// Najpierw klasa do materializa, potem grid view na material, potem grid page

	public static function button($label, $options = [], $icon = false)
	{
		$iconHtml = '';

		if($icon) $iconHtml = '<i class="material-icons right">'.$icon.'</i>';

		$data = self::tranfrormOptionsToHTMLDataAttributes($options);


		echo '<a '.$data.' class="waves-effect waves-light btn '.($options['class'] ?? '').'" '.(isset($options['id'])? 'id="'.$options['id'].'"' : '').'>'.$iconHtml.$label.'</a>';
	}

	public static function input($label, $name, $value = '', $options = [])
	{
		$id = $options['id'] ?? uniqid('mfi_');
		$class = $options['class'] ?? '';

		echo '
			<div class="input-field '.$class.'">
                <input name="'.$name.'" id="'.$id.'" type="text" value="'.$value.'">
                <label for="'.$id.'">'.$label.'</label>
             </div>
		';
	}

	public static function dropdown($label, $name, $values, $options = [])
	{
		$id = $options['id'] ?? $name;
		$options = '';

		if($values)
			foreach($values as $key=>$value)
				$options.='<option value="'.$key.'">'.$value.'</option>';

		echo '
			<div class="input-field">
				<select name="'.$name.'">
				  '.$options.'
				</select>
				<label>'.$label.'</label>
			  </div>
		';
	}

	public static function submit($label, $settings = [])
	{
		$confirm = $settings['confirm']?? false;

		echo '
		  <button '.(isset($settings['name'])? 'name="'.$settings['name'].'"' :  '').' class="btn waves-effect waves-light '.($settings['class'] ?? '').'" type="submit" '.($confirm ? "onclick=\"return confirm('Are you sure you want to delete this item?')\" " : "").'>'.$label.'
			<i class="material-icons right">'.($settings['icon'] ?? 'send').'</i>
		  </button>
		';
	}

	public static function checkbox($name, $settings = [])
	{
		echo '
			<div class="switch">
				<label>
				  Off
				  <input type="checkbox" name ="'.$name.'">
				  <span class="lever"></span>
				  On
				</label>
			  </div>
		';
	}

	private static function tranfrormOptionsToHTMLDataAttributes($options)
	{
		if(!$options) return '';

		$dataString = '';

		foreach($options as $key=>$value)
			if(strpos($key, 'data-') !== false)
				$dataString.=' '.$key.'="'.$value.'"';

		return $dataString;
	}
}
