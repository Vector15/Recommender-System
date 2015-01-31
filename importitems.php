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

// "ISBN";"Book-Title";"Book-Author";"Year-Of-Publication";"Publisher";"Image-URL-S";"Image-URL-M";"Image-URL-L"
//"User-ID";"Location";"Age"
$data = csv_to_array('moviedata/items.csv','|');
$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));
//movieID|Name|IMDBid|Year|url|rtid|crating|cscore|arating|ascore|genre

//1-
/*
foreach($data as $movie){
	$command = $client->getCommand('create_item', array('pio_iid' => $movie["movieID"]));
	$genre = $data = explode('^', $movie["genre"]);
	$command->setItypes($genre);
	$command->set('Name', $movie["Name"]);
	$command->set('IMDBid', $movie["IMDBid"]);
	$command->set('Year', $movie["Year"]);
	$command->set('url', $movie["url"]);
	$command->set('crating', $movie["crating"]);
	$command->set('cscore', $movie["cscore"]);
	$command->set('arating', $movie["arating"]);
	$command->set('ascore', $movie["ascore"]);
	$command->set('genre', $movie["genre"]);
	$client_response = $client->execute($command);
}*/

for($x=0; $x <= 1515 ; $x++){
	var_dump($data[$x]['movieID']);	
	$command = $client->getCommand('create_item', array('pio_iid' => $data[$x]["movieID"]));
 	$genre = explode('^', $data[$x]["genre"]);
	$command->setItypes($genre);
	$command->set('Name', $data[$x]["Name"]);
	$command->set('IMDBid', $data[$x]["IMDBid"]);
	$command->set('Year', $data[$x]["Year"]);
	$command->set('url', $data[$x]["url"]);
	$command->set('crating', $data[$x]["crating"]);
	$command->set('cscore', $data[$x]["cscore"]);
	$command->set('arating', $data[$x]["arating"]);
	$command->set('ascore', $data[$x]["ascore"]);
	$command->set('genre', $data[$x]["genre"]);
	$client_response = $client->execute($command);
}
/*
 foreach ($data as $movie) {
 	$command = $client->getCommand('create_item', array('pio_iid' => $movie['MovieID']));
 	$genre = $data = explode('|', $movie['Genres']);
	$command->setItypes($genre);
	$command->set('title', $movie['Title']);
	$command->set('genre', $movie['Genres']);
	$client_response = $client->execute($command);
 }
 */


