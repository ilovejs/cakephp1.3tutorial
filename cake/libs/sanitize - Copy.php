<?php
/**
 * Washes strings from unwanted noise.
 *
 * Helpful methods to make unsafe strings usable.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Data Sanitization.
 *
 * Removal of alpahnumeric characters, SQL-safe slash-added strings, HTML-friendly strings,
 * and all of the above on arrays.
 *
 * @package       cake
 * @subpackage    cake.cake.libs
 */
class Sanitize {
	private static $html_from_chars;
	private static $html_to_codes;
	
	private function initHtmlSpecialCharCodes() {
		$html_char_codes = array(
								chr(32)	 	=>  ' ',
								chr(38)		=>	'&amp;',
								chr(130)	=> ',',
								
								chr(132)	=> '"',
								chr(133)	=> '...',
								chr(134)	=> '**',
								chr(135)	=> '***',
								chr(136)	=> '^',
								chr(137)	=> 'o/oo',
								chr(138)	=> 'Sh',
								chr(139)	=> '<',
								chr(145)	=> "'",
								chr(146)	=> "'",
								chr(147)	=> '"',
								chr(148)	=> '"',
								chr(149)	=> '-',
								chr(150)	=> '&ndash;',
								chr(151)	=> '&mdash;',
								chr(152)	=> '~',
								chr(153)	=> '&trade;',
								chr(154)	=> 'sh',
								chr(155)	=> '>',
								chr(159)	=>	'&Yuml;',
								chr(160)	=>	'&nbsp;',
								chr(161)	=>	'&iexcl;',
								chr(162)	=>	'&cent;',
								chr(163)	=>	'&pound;',
								chr(164)	=>	'&curren;',
								chr(165)	=>	'&yen;',
								chr(166)	=>	'&brvbar;',
								chr(167)	=>	'&sect;',
								chr(168)	=>	'&uml;',
								chr(169)	=>	'&copy;',
								chr(170)	=>	'&ordf;',
								chr(171)	=>	'&laquo;',
								chr(172)	=>	'&not;',
								chr(173)	=>	'&shy;',
								chr(174)	=>	'&reg;',
								chr(175)	=>	'&macr;',
								chr(176)	=>	'&deg;',
								chr(177)	=>	'&plusmn;',
								chr(178)	=>	'&sup2;',
								chr(179)	=>	'&sup3;',
								chr(180)	=>	'&#180;',
								chr(181)	=>	'&micro;',
								chr(182)	=>	'&para;',
								chr(183)	=>	'&middot;',
								chr(184)	=>	'&cedil;',
								chr(185)	=>	'&sup1;',
								chr(186)	=>	'&ordm;',
								chr(187)	=>	'&raquo;',
								chr(188)	=>	'&frac14;',
								chr(189)	=>	'&frac12;',
								chr(190)	=>	'&frac34;',
								chr(191)	=>	'&iquest;',
								chr(192)	=>	'&Agrave;',
								chr(193)	=>	'&Aacute;',
								chr(194)	=>	'&Acirc;',
								chr(195)	=>	'&Atilde;',
								chr(196)	=>	'&Auml;',
								chr(197)	=>	'&Aring;',
								chr(198)	=>	'&AElig;',
								chr(199)	=>	'&Ccedil;',
								chr(200)	=>	'&Egrave;',
								chr(201)	=>	'&Eacute;',
								chr(202)	=>	'&Ecirc;',
								chr(203)	=>	'&Euml;',
								chr(204)	=>	'&Igrave;',
								chr(205)	=>	'&Iacute;',
								chr(206)	=>	'&Icirc;',
								chr(207)	=>	'&Iuml;',
								chr(208)	=>	'&ETH;',
								chr(209)	=>	'&Ntilde;',
								chr(210)	=>	'&Ograve;',
								chr(211)	=>	'&Oacute;',
								chr(212)	=>	'&Ocirc;',
								chr(213)	=>	'&Otilde;',
								chr(214)	=>	'&Ouml;',
								chr(215)	=>	'&times;',
								chr(216)	=>	'&Oslash;',
								chr(217)	=>	'&Ugrave;',
								chr(218)	=>	'&Uacute;',
								chr(219)	=>	'&Ucirc;',
								chr(220)	=>	'&Uuml;',
								chr(221)	=>	'&Yacute;',
								chr(222)	=>	'&THORN;',
								chr(223)	=>	'&szlig;',
								chr(224)	=>	'&agrave;',
								chr(225)	=>	'&aacute;',
								chr(226)	=>	'&acirc;',
								chr(227)	=>	'&atilde;',
								chr(228)	=>	'&#228;',								
								chr(229)	=>	'&aring;',
								chr(230)	=>	'&aelig;',
								chr(231)	=>	'&ccedil;',
								chr(232)	=>	'&egrave;',
								chr(233)	=>	'&eacute;',
								chr(234)	=>	'&ecirc;',
								chr(235)	=>	'&euml;',
								chr(236)	=>	'&igrave;',
								chr(237)	=>	'&iacute;',
								chr(238)	=>	'&icirc;',
								chr(239)	=>	'&iuml;',
								chr(240)	=>	'&eth;',
								chr(241)	=>	'&ntilde;',
								chr(242)	=>	'&ograve;',
								chr(243)	=>	'&oacute;',
								chr(244)	=>	'&ocirc;',
								chr(245)	=>	'&otilde;',
								chr(246)	=>	'&ouml;',
								chr(247)	=>	'&divide;',
								chr(248)	=>	'&oslash;',
								chr(249)	=>	'&ugrave;',
								chr(250)	=>	'&uacute;',
								chr(251)	=>	'&ucirc;',
								chr(252)	=>	'&uuml;',
								chr(253)	=>	'&yacute;',
								chr(254)	=>	'&thorn;',
								chr(255)	=>	'&yuml;',								
								chr(34)	=>	'&quot;',								
								chr(60)	=>	'&lt;',
								chr(62)	=>	'&gt;'								
								);
		
		// Get the from and to strings into separate arrays
		foreach ($html_char_codes as $from => $to) {
			self::$html_from_chars[] = $from;
			self::$html_to_codes[] = $to;
		}
	}
/**
 * Removes any non-alphanumeric characters.
 *
 * @param string $string String to sanitize
 * @param array $allowed An array of additional characters that are not to be removed.
 * @return string Sanitized string
 * @access public
 * @static
 */
	function paranoid($string, $allowed = array()) {
		$allow = null;
		if (!empty($allowed)) {
			foreach ($allowed as $value) {
				$allow .= "\\$value";
			}
		}

		if (is_array($string)) {
			$cleaned = array();
			foreach ($string as $key => $clean) {
				$cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $clean);
			}
		} else {
			$cleaned = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $string);
		}
		return $cleaned;
	}

/**
 * Makes a string SQL-safe.
 *
 * @param string $string String to sanitize
 * @param string $connection Database connection being used
 * @return string SQL safe string
 * @access public
 * @static
 */
	function escape($string, $connection = 'default') {
		$db =& ConnectionManager::getDataSource($connection);
		if (is_numeric($string) || $string === null || is_bool($string)) {
			return $string;
		}
		$string = substr($db->value($string), 1);
		$string = substr($string, 0, -1);
		return $string;
	}

/**
 * Returns given string safe for display as HTML. Renders entities.
 *
 * strip_tags() does not validating HTML syntax or structure, so it might strip whole passages
 * with broken HTML.
 *
 * ### Options:
 *
 * - remove (boolean) if true strips all HTML tags before encoding
 * - charset (string) the charset used to encode the string
 * - quotes (int) see http://php.net/manual/en/function.htmlentities.php
 *
 * @param string $string String from where to strip tags
 * @param array $options Array of options to use.
 * @return string Sanitized string
 * @access public
 * @static
 */
	function html($string, $options = array()) {
		static $defaultCharset = false;
		
		if ($defaultCharset === false) {
			$defaultCharset = Configure::read('App.encoding');
			if ($defaultCharset === null) {
				$defaultCharset = 'UTF-8';
			}
		}
		
		$default = array(
			'remove' => false,
			'charset' => $defaultCharset,
			'quotes' => ENT_QUOTES
		);

		$options = array_merge($default, $options);

		if ($options['remove']) {
			$string = strip_tags($string);
		}

		return htmlentities($string, $options['quotes'], $options['charset'], true);
	}

/**
 * Strips extra whitespace from output
 *
 * @param string $str String to sanitize
 * @return string whitespace sanitized string
 * @access public
 * @static
 */
	function stripWhitespace($str) {
		$r = preg_replace('/[\n\r\t]+/', '', $str);
		return preg_replace('/\s{2,}/', ' ', $r);
	}

/**
 * Strips image tags from output
 *
 * @param string $str String to sanitize
 * @return string Sting with images stripped.
 * @access public
 * @static
 */
	function stripImages($str) {
		$str = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $str);
		$str = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $str);
		$str = preg_replace('/<img[^>]*>/i', '', $str);
		return $str;
	}

/**
 * Strips scripts and stylesheets from output
 *
 * @param string $str String to sanitize
 * @return string String with <script>, <style>, <link> elements removed.
 * @access public
 * @static
 */
	function stripScripts($str) {
		return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/is', '', $str);
	}

/**
 * Strips extra whitespace, images, scripts and stylesheets from output
 *
 * @param string $str String to sanitize
 * @return string sanitized string
 * @access public
 */
	function stripAll($str) {
		$str = Sanitize::stripWhitespace($str);
		$str = Sanitize::stripImages($str);
		$str = Sanitize::stripScripts($str);
		return $str;
	}

/**
 * Strips the specified tags from output. First parameter is string from
 * where to remove tags. All subsequent parameters are tags.
 *
 * Ex.`$clean = Sanitize::stripTags($dirty, 'b', 'p', 'div');`
 *
 * Will remove all `<b>`, `<p>`, and `<div>` tags from the $dirty string.
 *
 * @param string $str String to sanitize
 * @param string $tag Tag to remove (add more parameters as needed)
 * @return string sanitized String
 * @access public
 * @static
 */
	function stripTags() {
		$params = params(func_get_args());
		$str = $params[0];

		for ($i = 1, $count = count($params); $i < $count; $i++) {
			$str = preg_replace('/<' . $params[$i] . '\b[^>]*>/i', '', $str);
			$str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
		}
		return $str;
	}

/**
 * Sanitizes given array or value for safe input. Use the options to specify
 * the connection to use, and what filters should be applied (with a boolean
 * value). Valid filters:
 *
 * - odd_spaces - removes any non space whitespace characters
 * - encode - Encode any html entities. Encode must be true for the `remove_html` to work.
 * - dollar - Escape `$` with `\$`
 * - carriage - Remove `\r`
 * - unicode -
 * - escape - Should the string be SQL escaped.
 * - backslash -
 * - remove_html - Strip HTML with strip_tags. `encode` must be true for this option to work.
 *
 * @param mixed $data Data to sanitize
 * @param mixed $options If string, DB connection being used, otherwise set of options
 * @return mixed Sanitized data
 * @access public
 * @static
 */
	function clean($data, $options = array()) {
		if (empty($data)) {
			return $data;
		}

		if (is_string($options)) {
			$options = array('connection' => $options);
		} else if (!is_array($options)) {
			$options = array();
		}

		$options = array_merge(array(
			'connection' => 'default',
			'odd_spaces' => true,
			'clean_word_char' => false,
			'remove_html' => false,			
			'encode' => true,
			'dollar' => true,
			'carriage' => true,
			'unicode' => true,
			'escape' => true,
			'backslash' => true
		), $options);

		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$data[$key] = Sanitize::clean($val, $options);
			}
			return $data;
		} else {
			if ($options['odd_spaces']) {
				$data = str_replace(chr(0xCA), '', str_replace(' ', ' ', $data));
			}
			if ($options['clean_word_char']) {			
				$data = Sanitize::clean_up_msword($data);
			}
			if ($options['encode']) {
				$data = Sanitize::html($data, array('remove' => $options['remove_html']));
			}
			if ($options['dollar']) {
				$data = str_replace("\\\$", "$", $data);
			}
			if ($options['carriage']) {
				$data = str_replace("\r", "", $data);
			}

			$data = str_replace("'", "'", str_replace("!", "!", $data));

			if ($options['unicode']) {
				$data = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $data);
			}
			if ($options['escape']) {
				$data = Sanitize::escape($data, $options['connection']);
			}
			if ($options['backslash']) {
				$data = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $data);
			}
			return $data;
		}
	}
	
	function clean_up_msword($input_string){
		$processed_string = null;
		
		// Initialise the html chars if they are not already done
		if (!(self::$html_from_chars && self::$html_to_codes)) {
			Sanitize::initHtmlSpecialCharCodes();
		}
		
		// Process the string
		if ($input_string) {
			$processed_string = str_replace(self::$html_from_chars,self::$html_to_codes,$input_string);
		}
		
		return $processed_string;
	}

/**
 * Formats column data from definition in DBO's $columns array
 *
 * @param Model $model The model containing the data to be formatted
 * @access public
 * @static
 */
	function formatColumns(&$model) {
		foreach ($model->data as $name => $values) {
			if ($name == $model->alias) {
				$curModel =& $model;
			} elseif (isset($model->{$name}) && is_object($model->{$name}) && is_subclass_of($model->{$name}, 'Model')) {
				$curModel =& $model->{$name};
			} else {
				$curModel = null;
			}

			if ($curModel != null) {
				foreach ($values as $column => $data) {
					$colType = $curModel->getColumnType($column);

					if ($colType != null) {
						$db =& ConnectionManager::getDataSource($curModel->useDbConfig);
						$colData = $db->columns[$colType];

						if (isset($colData['limit']) && strlen(strval($data)) > $colData['limit']) {
							$data = substr(strval($data), 0, $colData['limit']);
						}

						if (isset($colData['formatter']) || isset($colData['format'])) {

							switch (strtolower($colData['formatter'])) {
								case 'date':
									$data = date($colData['format'], strtotime($data));
								break;
								case 'sprintf':
									$data = sprintf($colData['format'], $data);
								break;
								case 'intval':
									$data = intval($data);
								break;
								case 'floatval':
									$data = floatval($data);
								break;
							}
						}
						$model->data[$name][$column]=$data;
						/*
						switch ($colType) {
							case 'integer':
							case 'int':
								return  $data;
							break;
							case 'string':
							case 'text':
							case 'binary':
							case 'date':
							case 'time':
							case 'datetime':
							case 'timestamp':
							case 'date':
								return "'" . $data . "'";
							break;
						}
						*/
					}
				}
			}
		}
	}
}
