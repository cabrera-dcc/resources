<?php
require("data.php");

function download($target)
{
	if($target != ""){
		downloadExcelFile($target);
	}
}

function downloadExcelFile($target) {
		try {
			$data = new Data();
			$contactsInfo =  $data->getHTML($data->getRows($target),$target);

			header('Content-Encoding: UTF-8');
			header("Content-type: application/vnd.ms-excel;charset=utf-8");
			header("Content-Disposition: attachment;Filename=$target.xls");

			echo $contactsInfo;

		} catch(PDOException $e) {
			echo 'An error ocurred when connecting to the database. Please, check your password';
		}				
	
}