<?php 
defined('__DIR__') || define('__DIR__', dirname(__FILE__));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__ . '/../../application'));

$checklist = array();

include_once __DIR__ . '/Check.php';

$check = new Check();
$check->check();
$check->checkCfg(1);

$script = '';

foreach ($check->getCheckList() as $checkItem) :
if ($checkItem['code'] == -1) {
	$spanClass = 'ui-icon ui-icon-red ui-icon-alert';
	$liClass = 'red';
} else if ($checkItem['code'] == 0) {
	$spanClass = 'ui-icon ui-icon-red ui-icon-notice';
	$liClass = 'orange';
} else {
	if ($checkItem['canBeBetter'] == 1) {
		$liClass = 'orange';
		$spanClass = 'ui-icon ui-icon-red ui-icon-notice';
	} else {
		$spanClass = 'ui-icon ui-icon-bluelight ui-icon-check';
		$liClass = '';
	}
}

if ($checkItem['alt'] != '') {
	$liClass .= ' tipsyauto';
}
if (($checkItem['alt'] != '')) {
	$title = $checkItem['alt'];
}
else {
	$title = '';
}
$script .= '<li class="'.$liClass.'" title="'.$title.'">';
$script .= '<span class="'.$spanClass.'"></span>'.$checkItem['text']."</li>";

endforeach;

echo $script;
?>