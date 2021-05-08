<?php // compress and merge CSS files
header('Content-type: text/css');
ob_start("compress");

function compress($buffer) {
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
	return $buffer;
}
include_once(dirname(__FILE__).'/bootstrap.min.css');
include_once(dirname(__FILE__).'/font-awesome.min.css');
include_once(dirname(__FILE__).'/jquery-ui.min.css');
// include_once(dirname(__FILE__).'css/jquery-ui.structure.min.css');
include_once(dirname(__FILE__).'/jquery-ui.theme.min.css');
include_once(dirname(__FILE__).'/normalize.css');
include_once(dirname(__FILE__).'/style.css');
include_once(dirname(__FILE__).'/component.css');

ob_end_flush();
?>