<?php
/**
 * HTML Forms class
 *
 * @package		Classes
 * @subpackage	Form
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license
 *
 * <p>Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania</p>
 *
 * <p>W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.</p>
 */

if(!empty($_POST)) {
	Form::$post = $_POST;
}
if(!empty($_GET)) {
	Form::$get = $_GET;
}

class Form {

	public static $post;
	public static $get;
	public static $token;

	public static function open( $method = 'post', $action = null, $files = null, $module = true, $css = 'form-horizontal' ) {

		$method = (!empty($method) ? ' method="' . $method . '" ' : '');
		$action = (!empty($action) ? ' action="' . $action . '" ' : '');
		$files = (!empty($files) ? ' enctype="multipart/form-data"' : '');

		echo '<form '.(isset($css) ? 'class="'.$css.'"' : '').' '. (isset($action) ? $action : ''). $method . (isset($files) ? $files : '') . ' data-toggle="validator">';
		if($module == true) {
			echo '<input type="hidden" name="module" value="true" />';
			echo '<input type="hidden" name="token" value="' . Kernel::generateToken(12) . '" />';
		}
	}

	protected static $open2_options = [
	 	'method' => 'POST',
	 	'files' => false,
	 	'module' => true,
	 	'class' => 'form-horizontal'
 	];

/**
 *	Form::open2( $options )
 *	@since 1.3
 *
 *	@params enum 	$options[method] 	(POST,GET) default POST
 *	@params url 	$options[action] 	Form action url
 *	@params boolean $options[files] 	If form is using uploading set this to true (default false)
 *	@params boolean $options[module] 	If is true, adding hidden input with module true value (default true)
 *	@params string 	$options[class] 	Own class parametr (default form-horizontal)
 *	@params string	$options[id] 		Own id parametr (default null)
 *
 *	@example Usage in smarty {Form::open2([ "method" => "GET", "action" => "/url/url", "files" => true ])}
 *
 *	@return string
 */

 	public static function open2( $options )
 	{
	 	if(empty($options)) {
		 	$options = self::$open2_options;
	 	}
	 	$method = (!empty($option['method']) ? $option['method'] : 'POST');
	 	$action = (!empty($option['action']) ? $option['action'] : null);
	 	$files 	= (isset($option['files'])	 ? $option['files']  : false);
	 	$module = (isset($option['module'])	 ? $option['module'] : true);
	 	$class 	= (isset($option['class'])   ? $option['class']  : 'form-horizontal');
	 	$id 	= (isset($option['id']) 	 ? $option['id'] 	 : null);

	 	return '<form' . (!empty($method) ? ' method="' . $method . '"' : '') .
	 	(!empty($action) ? ' action="' . $action . '"' : '') .
	 	(!empty($id) ? ' id="' . $id . '"' : '') .
	 	(!empty($class) ? ' class="' . $class . '"' : '') .
	 	($files == true ? ' enctype="multipart/form-data"' : '') . '>' .
	 	(!empty($module) ? '<input type="hidden" name="module" value="true" />' : '');
 	}

	public static function close()
	{
		echo '</form>';
	}

/**
Form::number($name, $minmax, $required)

@name: input name=$name
@minmax: 0,5 - minimal number and maximal number
@required: if field is required
**/

	public static function number($name, $minmax = '', $required = '')
	{
		$value = (!empty(self::$post[$name]) ? ' value="'.self::$post[$name].'"' : '');
		$name = (!empty($name) ? ' name="'.$name.'"' : '');
		$required = (($required == true) ? ' required' : '');

		if(isset($minmax)) {
			$minmax = explode("," , $minmax);
			$min = ' min="'.$minmax[0].'"';
			if(isset($minmax[1])) {
				$max = ' max="'.$minmax[1].'"';
			}
		}

		return '<input class="form-control" type="number"' .
		(isset($name) ? $name : '') .
		(isset($value) ? $value : '') .
		(isset($required) ? $required : '') .
		(isset($min) ? $min : '') .
		(isset($max) ? $max : '') . '/>';

	}

	public static function url($name, $required = '')
	{
		$value = (!empty(self::$post[$name]) ? ' value="'.self::$post[$name].'"' : '');
		$name = (!empty($name) ? ' name="'.$name.'"' : '');
		$required = (($required == true) ? ' required' : '');

		return '<input class="form-control" type="url"' .
		(isset($name) ? $name : '') .
		(isset($value) ? $value : '') .
		(isset($required) ? $required : '') . '/>';

	}

	public static function date($name, $required = null)
	{
		$value = (!empty(self::$post[$name]) ? ' value="'.self::$post[$name].'"' : '');
		$name = (!empty($name) ? ' name="'.$name.'"' : '');
		$required = (($required == true) ? ' required ' : '');

		return '<input class="form-control dataPicker" type="text"' .
		(isset($name) ? $name : '') .
		(isset($value) ? $value : '') .
		(isset($required) ? $required : '') . '/>';

	}

	public static function datetime($name, $required = null)
	{
		$value = (!empty(self::$post[$name]) ? ' value="'.self::$post[$name].'"' : '');
		$name = (!empty($name) ? ' name="'.$name.'"' : '');
		$required = (($required == true) ? ' required ' : '');

		return '<input class="form-control dataTimePicker" type="text"' .
		(isset($name) ? $name : '') .
		(isset($value) ? $value : '') .
		(isset($required) ? $required : '') . '/>';

	}

	public static function date2( $option )
	{
		if(!empty($option['calendar_from_today'])) {
			$from_today = true;
		} else {
			$from_today = false;
		}

		return self::date(
			$option['name'] ,
			(!empty($option['required']) ? $option['required'] : null),
			(!empty($option['value']) ? $option['value'] : null),
			(!empty($option['time']) ? $option['time'] : null),
			$from_today
		);
	}

	public static function upload($name, $multiple = null, $required = null, $accept = null)
	{
		echo '<input type="file" name="' . $name .
		(!empty($multiple) ? "[]" : "") . '" ' .
		(!empty($multiple) ? 'multiple' : '') . ' ' .
		(isset($required) ? 'required' : '') .' ' .
		(!empty($accept) ? 'accept="' . $accept . '"' : '') . '/>';
	}

	public static function input($type = 'text', $name = '', $options = '') {

		if( $type == 'submit') {
			trigger_error("ERROR: Please use <b>Form::submit()</b> insted of <b>Form::input('submit')</b>");
		}

		if( (isset(self::$post['0'])) AND (count(self::$post['0']) > 0 )) {
			foreach(self::$post as $k=>$i) {
				if($i['name'] == $name) {
					$value = (!empty($i['value']) ? ' value="'.$i['value'].'"' : '');
				}
			}
		} else {
			$value = (!empty(self::$post[$name]) ? ' value="'.self::$post[$name].'"' : '');
		}

		$type = (!empty($type) ? ' type="'.$type.'"' : '');
		$name = (!empty($name) ? ' name="'.$name.'" id="'.$name.'"': '');

		if(!empty($options)) {
			$options = self::readOptions($options);
			if(is_array($options)) {
				foreach($options as $k=>$i) {
					if( $i['name'] == "required" ) {
						$required = (($i['value'] == true) ? ' required ' : '');
					}

					if( $i['name'] == "mask") {
						$mask = ( ($i['value']) ? " data-inputmask=\"'mask': '".$i['value']."'\" data-mask" : "" );
					}

					if( $i['name'] == "mask-alias") {
						$mask = ( ($i['value']) ? " data-inputmask=\"'alias': '".$i['value']."'\" data-mask" : "" );
					}

					if( $i['name'] == "placeholder") {
						$placeholder = ( ($i['value']) ? "placeholder=\"".$i['value']."\"" : "" );
					}

					if( $i['name'] == "css") {
						$css = ( ($i['value']) ? $i['value'] : "" );
					}
				}
			}
		}

		echo '<input class="form-control '.(isset($css) ? $css : '').'" ' . (isset($type) ? $type : '') . (isset($name) ? $name : '') . (isset($value) ? $value : '') . (!empty($required) ? $required : '') . (isset($mask) ? $mask  : '') . (isset($placeholder) ? $placeholder  : '') . '/>';

		if(isset($required)) {
			echo '<div class="help-block with-errors"></div>';
		}


	}

	public static function hidden($name, $value = null)
	{
		echo '<input type="hidden" name="'.$name.'" value="'.$value.'"/>';
	}

	public static function submit( $value, $name='' ) {
		echo '<input type="submit" name="'.$name.'" value="' . $value . '" class="btn btn-primary"/>';
	}

	public static function checkbox2( $options = null )
	{
		$name = (isset($options['name']) ? $options['name'] : null);
		$value = (isset($options['value']) ? $options['value'] : null);
		$label = (isset($options['label']) ? $options['label'] : null);
		$field = (isset($options['field']) ? $options['field'] : null);
		$checked = (isset($options['checked']) ? $options['checked'] : null);
		$class = (isset($options['class']) ? $options['class'] : null);
		$data = (isset($options['data']) ? $options['data'] : null);
		$as_button = (isset($option['as_button']) ? $option['as_button'] : false);

		return self::checkbox( $name, $value, $label, $field, $checked, $class, $data, $as_button );
	}

	public static function checkbox3( $name, $value, $label = null )
	{
		$checked = false;

		if(!empty(Form::$post)) {
			foreach(Form::$post as $k=>$i) {
				$name_p = preg_replace("/\[([0-9]+)\]/" , "" , $name);
				if($k == $name_p) {
					if(is_array($i)) {
						foreach($i as $v) {
							if($v == $value) {
								$checked = ' checked';
							}
						}
					}
					if($i == $value) {
						$checked = ' checked';
					}
				} else {
					$name_p = preg_replace("/\[[(a-z)]+\]/" , "" , $name);
					$name_p = preg_replace("/\[\]/" , "" , $name);
					if($k == $name_p) {
						if(is_array($i)) {
							foreach($i as $v) {
								if($v == $value) {
									$checked = ' checked';
								}
							}
						}
						if($i == $value) {
							$checked = ' checked';
						}
					}
				}
			}
		}

		if(!empty(Form::$get)) {
			foreach(Form::$get as $k=>$i) {
				$name_p = preg_replace("/\[([0-9]+)\]/" , "" , $name);
				if($k == $name_p) {
					if(is_array($i)) {
						foreach($i as $v) {
							if($v == $value) {
								$checked = ' checked';
							}
						}
					}
					if($i == $value) {
						$checked = ' checked';
					}
				} else {
					$name_p = preg_replace("/\[[(a-z)]+\]/" , "" , $name);
					$name_p = preg_replace("/\[\]/" , "" , $name);
					if($k == $name_p) {
						if(is_array($i)) {
							foreach($i as $v) {
								if($v == $value) {
									$checked = ' checked';
								}
							}
						}
						if($i == $value) {
							$checked = ' checked';
						}
					}
				}
			}
		}

		if((isset($checked)) && ($checked == true)) {
			$checked = ' checked';
		}


		echo '<div class="checkbox">' .
			'<label><input data-toggle="checkbox-x" data-three-state="false"
			type="checkbox" ' . (isset($class) ? ' class="' . $class . '" ' : ' ') .
			(!empty($id) ? 'id="'. $id . '" ' : '') .
			'name="'.$name.'" value="'.$value.'" ' .
			(($checked == true) ? ' checked' : '') . ' /> '.$label . '</label></div>';
	}

	public static function checkbox( $name, $value, $label = null, $field = null, $checked = false, $class = false, $data = null, $as_button = false)
	{
		if(!empty(Form::$post)) {
			foreach(Form::$post as $k=>$i) {
				$name_p = preg_replace("/\[([0-9]+)\]/" , "" , $name);
				if($k == $name_p) {
					if(is_array($i)) {
						foreach($i as $v) {
							if($v == $value) {
								$checkable = ' checked';
							}
						}
					}
					if($i == $value) {
						$checkable = ' checked';
					}
				} else {
					$name_p = preg_replace("/\[[(a-z)]+\]/" , "" , $name);
					$name_p = preg_replace("/\[\]/" , "" , $name);
					if($k == $name_p) {
						if(is_array($i)) {
							foreach($i as $v) {
								if($v == $value) {
									$checkable = ' checked';
								}
							}
						}
						if($i == $value) {
							$checkable = ' checked';
						}
					}
				}
			}
		}
		if((isset($checked)) && ($checked == true)) {
			$checkable = ' checked';
		}

		if(strpos($name, "[]") !== false) {
			$id = "";
			$class = str_replace("[]" , "" , $name);
		} else {
			$id = $name;
		}

		if(!empty($data)) {
			$data_attr = implode(" " , $data);
		}

		if($value == 'TRUE') {
			$value = 'TRUE';
		}

		if( Kernel::$CheckBox == true) {
			echo '<div class="checkbox"><label><input data-toggle="checkbox-x" data-three-state="false" type="checkbox" ' .
			(isset($class) ? ' class="' . $class . '" ' : ' ') .
			(!empty($id) ? 'id="'. $id . '" ' : '') . 'name="'.$name.'" value="'.$value.'" ' .
			(isset($checkable) ? $checkable : '') .' /> '.$label.' ' .
			(isset($checkable) ? 1 : 0) . '</label></div>';
		} else {
			echo '<label><input type="checkbox" ' .
			(isset($data_attr) ? $data_attr : "") ." " .
			(isset($class) ? ' class="' . $class . '" ' : ' ') .
			(!empty($id) ? 'id="'. $id . '" ' : '') . 'name="'.$name.'" value="'.$value.'" ' .
			(isset($checkable) ? $checkable : '') .' /> '.$label.'</label>';
		}

		unset($checkable);
	}

	public static function radio( $name, $value, $label, $class = '' )
	{
		if(is_array( Form::$post )) {
			$checkable = "";
			foreach( Form::$post as $key=>$item ) {
				if(!is_array($item)) {
					if(($key == $name) AND ($item== $value)) {
						$checkable = " checked";
					}
				} else {
					if(($item['name'] == $name) AND ($item['value'] == $value)) {
						$checkable = " checked";
					}
				}
			}
		}
		return '<input type="radio" data-switch-set="size" data-switch-value="mini" class="'.$class.' switch" name="'.$name.'" value="'.$value.'" '.(isset($checkable) ? $checkable : '') . '/> '.$label.'';
	}

	public static function text( $id, $name, $value = null, $size = null, $options = null )
	{
		$value = (!empty(self::$post[$name]) ? $value = self::$post[$name] : $value);
		if(!is_null($size)) {
			$size = explode("," , $size);
			$size_row = $size[0];
			$size_col = $size[1];
		} else {
			$size_row = "2";
			$size_col = "60";
		}

		if(!empty($options)) {
			$options = self::readOptions($options);
			if(is_array($options)) {
				foreach($options as $k=>$i) {
					if( $i['name'] == "required" ) {
						$required = (($i['value'] == true) ? ' required ' : '');
					}

					if( $i['name'] == "placeholder") {
						$placeholder = ( ($i['value']) ? "placeholder=\"".$i['value']."\"" : "" );
					}

					if( $i['name'] == "css") {
						$css = ( ($i['value']) ? $i['value'] : "" );
					}
				}
			}
		}


		echo '<textarea rows="'.$size_row.'" cols="'.$size_col.'"' . (isset($required) ? ' required' : '') . ' ' . (isset($placeholder) ? $placeholder  : '') . ' class="form-control"'.(isset($id) ? ' id="'.$id.'"' : '') . ' name="'.$name.'">'.$value.'</textarea>';
	}

	public static function select( $name, $array, $required = '', $emptyname = '', $value = null, $multiple = '' )
	{
		$required = (($required == true) ? ' required' : '');
		$multiple = (($multiple == true) ? ' multiple' : '');
		$multi = (($multiple == true) ? '[]' : '');
		echo '<select class="form-control" id="' . $name . '" name="' . $name . $multi . '"' . $required . ' ' . $multiple . '>';
		if(!empty($emptyname)) {

			echo self::$post[$name];
			echo '<option value="'.$value.'" ' . (self::$post[$name] == 0 ? 'selected' : '') . '>'.$emptyname.'</option>';
		}
		if(is_array($array)) {
			foreach($array as $i) {
				$on=false;
				if(!empty($i['data'])) {
					foreach($i['data'] as $n=>$v) {
						$data[] = 'data-' . $n . '="' . $v . '"';
					}
				}

				if( !empty(self::$post[$name]) && is_array( self::$post[$name] )) {
					foreach (self::$post[$name] as $key => $value) {
						if(self::$post[$name][$key] == $i['id']) {
							echo '<option value="' . $i['id'] . '" selected ' . (!empty($data) ? implode(" ", $data) : "") . '>'.$i['name'].'</option>' . PHP_EOL;
							$on = true;
						} else {
							//echo '<option value="' . $i['id'] . '">'.$i['name'].'1a</option>';
						}
					}
					if($on != true) {
						echo '<option value="' . $i['id'] . '" ' . (!empty($data) ? implode(" ", $data) : "") . '>'.$i['name'].'</option>' . PHP_EOL;
					}
				} else {
					if((isset(self::$post[$name])) && (self::$post[$name] == $i['id'])) {
						echo '<option value="' . $i['id'] . '" selected ' . (!empty($data) ? implode(" ", $data) : "") . '>'.$i['name'].'</option>' . PHP_EOL;
					} else {
						echo '<option value="' . $i['id'] . '" ' . (!empty($data) ? implode(" ", $data) : "") . '>'.$i['name'].'</option>' . PHP_EOL;
					}
				}
				unset($data);
			}
		}
		echo '</select>';
		if(isset($required)) {
			echo '<div class="help-block with-errors"></div>';
		}
	}

	public static $replacement = array(
		"\," => "{comma}",
		"\:" => "{dots}",
		"\'" => "{quote}",
		"\-" => "{minus}"
	);

	public static function readOptions( $string )
	{
		$options = strtr($string, self::$replacement);
		$options = explode("," , $options);
		if(is_array($options)) {
			foreach($options as $item) {
				if(!empty($item)) {
					$result = explode(":" , $item);
					$k = $result[0];
					if(isset($result[1])) {
						$v = $result[1];

						$v = str_replace(chr(39) , "" , $v);
						$v = stripslashes(strtr($v, array_flip(self::$replacement)));
					}
					$params[] = array("name" => trim($k), "value" => (isset($v) ? $v : ''));
				}
			}
			if(isset($params)) {
				return $params;
			}
		}

	}

	public static function input2( $option = null )
		{
 		global $request;

		if($option == null) {
			echo ("Form::input2 needs \$option param to be an array");
			exit;
		}

		$name = $option['name'];
		if(isset($request->get[ $name ])) {
			self::$post[ $name ] = $request->get[ $name ];
		}

		if(!empty(self::$post)) {
			foreach(self::$post as $i) {
				// sprawdzenie czy istnieje wartosc POST[nazwa]
				if(isset(self::$post[ $name ])) {
					$value = self::$post[ $name ];
				} else {
					// sprawdzenie czy istnieje wartość POST[nazwa][id]
					preg_match('/([^.?\[?\]]+)/' , $name, $matches);
					if(isset($matches['0'])) {
						$name_multi = $matches['0'];
						unset($matches);
					}

					// jeżeli nie jest to checkbox ani radio
					if( !in_array($option['type'], [ "checkbox" , "radio" ])) {
						preg_match('/\[([^.]+)\]/' , $name, $matches);
						if(isset($matches['1'])) {
							$key = $matches['1'];
							unset($matches);
						}

						if((isset($name_multi)) && (isset($key))) {
							if(isset(self::$post[ $name_multi ][ $key ])) {
								$value = self::$post[ $name_multi ][ $key ];
							}
						}
					// jeżeli jest to checkbox lub radio
					} else {
						preg_match('/([' . $name_multi . ']+)\\[([0-9]+)\\]/', $name, $matches);
						if(isset($matches['2'])) {
							$id = (int) $matches['2'];
							unset($matches);
						}

						if((isset($name_multi)) && (isset($id))) {
							if(isset( self::$post[$name_multi][$id])) {
								foreach(self::$post[$name_multi] as $k2=>$i2) {
									if( ($i2 == $option['value']) && (in_array($id, $option['value']))) {
										$checked = true;
									}
								}
							}
						}
					}


				}
			}
		} else {
			$value = '';
		}

		if(!empty($option['type']) && in_array($option['type'], ['checkbox','radio'])) {
			//$class = (isset($option['class']) ? ' ' . $option['class'] : '');
			$class = ' class="form-control' . (isset($option['class']) ? ' ' . $option['class'] : '') . '"';
			$html = '<label class="checkbox">';
		} else {
			$class = ' class="form-control' . (isset($option['class']) ? ' ' . $option['class'] : '') . '"';
			$html = PHP_EOL;
		}

		$html .= '<input' .
		(isset($option['id']) ? ' id="' . $option['id'] . '"' : '') .
		(isset($option['type']) ? ' type="' . $option['type'] . '"' : '') .
		(isset($option['name']) ? ' name="' . $option['name'] . '"' : '') .
		(isset($option['data-content']) ? ' data-content="' . $option['data-content'] . '"' : '') .
		(isset($checked) ? ' checked' : '') .
		(isset($option['required']) ? ' required' : '') .
		(isset($option['disabled']) ? ' disabled' : '') .
		(isset($option['readonly']) ? ' readonly' : '') .
		(isset($option['autocomplete']) ? ' autocomplete="' . $option['autocomplete'] . '"' : '') .
		(isset($option['max']) ? ' max="' . $option['max'] . '"' : '') .
		(isset($option['min']) ? ' min="' . $option['min'] . '"' : '') .
		(isset($option['maxlength']) ? ' maxlength="' . $option['maxlength'] . '"' : '') .
		(isset($option['step']) ? ' step="' . $option['step'] . '"' : '') .
		(isset($option['pattern']) ? ' pattern="' . $option['pattern'] . '"' : '') .
		(isset($option['placeholder']) ? ' placeholder="' . $option['placeholder'] . '"' : '') .
		(isset($option['value']) ? ' value="' . (is_array($option['value']) ? 1 : $option['value']) . '"' : (isset($value) ? ' value="' . $value . '"' : "") ) .
		$class . ' />';

		if(!empty($option['type']) && in_array($option['type'], ['checkbox','radio'])) {
			$html .= (isset($option['label']) ? ' ' . $option['label'] : "") . '</label>';
		}
		unset($checked);
		return $html;

		}

		public static function text2( $option = null )
		{
 		global $request;
 		if($option == null) {
	 		echo ("Form::text2 needs \$option param to be an array");
			exit;
 		}

 		$name = $option['name'];
 		if(isset($request->get[ $name ])) {
			self::$post[ $name ] = $request->get[ $name ];
		}

		if(!empty(self::$post)) {
			foreach(self::$post as $i) {
				// sprawdzenie czy istnieje wartosc POST[nazwa]
				if(isset(self::$post[ $name ])) {
					$value = self::$post[ $name ];
				} else {
					// sprawdzenie czy istnieje wartość POST[nazwa][id]
					preg_match('/([^.?\[?\]]+)/' , $name, $matches);
					if(isset($matches['0'])) {
						$name_multi = $matches['0'];
						unset($matches);
					}

					// jeżeli nie jest to checkbox ani radio
					if( isset($option['type']) && !in_array($option['type'], [ "checkbox" , "radio" ])) {
						preg_match('/\[([^.]+)\]/' , $name, $matches);
						if(isset($matches['1'])) {
							$key = $matches['1'];
							unset($matches);
						}

						if((isset($name_multi)) && (isset($key))) {
							if(isset(self::$post[ $name_multi ][ $key ])) {
								$value = self::$post[ $name_multi ][ $key ];
							}
						}
					// jeżeli jest to checkbox lub radio
					} else {
						preg_match('/([' . $name_multi . ']+)\\[([0-9]+)\\]/', $name, $matches);
						if(isset($matches['2'])) {
							$key = (int) $matches['2'];
							unset($matches);
						}

						if((isset($name_multi)) && (isset($key))) {
							if(isset( self::$post[$name_multi][$key])) {
								foreach(self::$post[$name_multi] as $k2=>$i2) {
									if( $i2 == $option['value'] ) {
										$checked = true;
									}
								}
							}
						}
					}


				}
			}
		} else {
			$value = '';
		}

 		echo '<textarea' .
 		(isset($option['id']) ? ' id="' . $option['id'] . '"' : '') .
 		(isset($option['name']) ? ' name="' . $option['name'] . '"' : '') .
 		(isset($option['placeholder']) ? ' placeholder="' . $option['placeholder'] . '"' : '') .
 		(isset($option['maxlength']) ? ' maxlength="' . $option['maxlength'] . '"' : '') .
 		(isset($option['rows']) ? ' rows="' . $option['rows'] . "'" : 'rows="5"') .
 		(isset($option['cols']) ? ' cols="' . $option['cols'] . "'" : 'cols="10"') .
 		(isset($option['required']) ? ' required' : '') .
 		(isset($option['disabled']) ? ' disabled' : '') .
 		' class="form-control' . (isset($option['class']) ? ' ' . $option['class'] : '')  . '"' .
 		'>' . (isset($value) ? $value : '') . '</textarea>';
		}

	public static function select2( $option = null )
	{
		global $request;

		if($option == null) {
			echo ("Form::select2 needs \$option param to be an array");
			exit;
		}

		$html[] = '<select' .
		(empty($option['live_search']) ? ' data-live-search="true"' : ' data-live-search="' . $option['live_search'] . '"') .
		(isset($option['id']) ? ' id="' . $option['id'] . '"' : '') .
		(isset($option['name']) ? ' name="' . $option['name'] . '"' : '') .
		(isset($option['required']) ? ' required' : '') .
		(isset($option['multiple']) ? ' multiple' : '') .
		(isset($option['disabled']) ? ' disabled' : '') .
		' class="form-control' . (isset($option['class']) ? ' ' . $option['class'] : '') . '">';

		$option['empty'] = (isset($option['empty']) ? $option['empty'] : 'true');
		$option['selected'] = (isset($option['selected']) ? $option['selected'] : 0);

		if(!empty($option['values'])) {
			if(isset($option['empty']) && ($option['empty'] == true)) {
				$html[] = '<option value="">' . (isset($option['empty_name']) ? $option['empty_name'] : '- wybierz -') . '</option>';
			}

			foreach($option['values'] as $i) {

				if(!empty($i['sub'])) {
					$addon = 'data-subtext="' . $i['sub'] . '"';
				}
				$name = str_replace("[]" , "", $option['name']);

				// sprawdzenie czy istnieje wartość w GET
				if(!empty($request->get[ $name ])) {
					self::$post[ $name ] = $request->get[ $name ];
				}

				// sprawdzenie czy istnieje wartosc POST[nazwa]
				if(!empty(self::$post[ $name ])) {
					if( !is_array( self::$post[$name] )) {
						if(self::$post[$name] == $i['id']) {
							$selected = true;
						}
					} else {
						foreach( self::$post[$name] as $vi) {
							if($vi == $i['id']) {
								$selected = true;
							}
						}
					}
				} else {
					// sprawdzenie czy istnieje wartość POST[nazwa][id]
					preg_match('/([^.?\[?\]]+)/' , $name, $matches);
					if(isset($matches['0'])) {
						$name_multi = $matches['0'];
						unset($matches);
					}

					preg_match('/\[([^.]+)\]/' , $name, $matches);
					if(isset($matches['1'])) {
						$key = $matches['1'];
						unset($matches);
					}
					if((isset($name_multi)) && (isset($key))) {
						if(isset(self::$post[ $name_multi ][ $key ])) {
							if( self::$post[$name_multi][$key] == $i['id'] ) {
								$selected = true;
							}
						}
					}
				}

				if((!isset($selected)) AND (!empty($option['selected']))){
					if($option['selected'] == $i['id']) {
						$selected = true;
					}
				}

				if(!empty($option['data'])) {
					foreach($option['data'] as $data_key=>$data_val) {
						$data_elem[] = 'data-' . $data_key . '="' . $data_val . '" ';
					}
				}

				$html[] = '<option value="' . $i['id'] . '"' . (isset($selected) ? ' selected' : '') . (isset($data_elem) ? ' ' . implode(" " , $data_elem) : '') . '>' . $i['name'] . '</option>';

				if(!empty($selected)) {
					unset( $selected );
				}
			}
		}

		$html[] = '</select>';

		if(!empty($html)) {
			return implode( PHP_EOL , $html);
		}
	}
}
