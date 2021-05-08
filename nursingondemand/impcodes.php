<?php
// Current Date
$date = date('Y-m-d');
// Timestamp Value from date
$strtotime = strtotime($date);
//Create Date of any date
$created_date = date_create($date);

date_default_timezone_set("Asia/Kolkata");
date("h:i:sa");

$assarray  = array(
	array('id' => 1,
		'name'=>'kapil',
		'Designation'=>'PHP Developer'),
	array('id' => 2,
		'name'=>'kapil 2',
		'Designation'=>'PHP Developer 2')
	);

$multiarray  = array(
	array('id' => 1,
		'name'=>'kapil',
		'Designation'=>array('post' => 'PHP Developer')),
	);
echo "<pre>";print_r($multiarray);
?>