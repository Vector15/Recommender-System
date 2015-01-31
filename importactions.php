<?php
session_start();
require 'vendor/autoload.php';
//require_once('FirePHPCore/fb.php');
use PredictionIO\PredictionIOClient;

function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	return $data;
}

$data = csv_to_array('moviedata/userstomovie.csv');
$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));
//userID,movieID,rating,timestamp
//var_dump($data);
try{
	foreach ($data as $rating) {
		$client->identify($rating['userID']);
			$client_response = $client->execute($client->getCommand(
		    'record_action_on_item',array('pio_action' => 'rate', 'pio_iid' => $rating['movieID'], 'pio_rate' => $rating['rating'])));	
	 }
}catch(Exception $e){
		    echo 'Caught exception: ', $e->getMessage(), "\n";
		}

