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
$data = csv_to_array('moviedata/users.csv','|');
$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));
//userID|age|gender|occupation|zipcode
//1-943
//var_dump($data);
for($x=0; $x <= 943; $x++){
	$command = $client->getCommand('create_user', array('pio_uid' => $data[$x]['userID']));
	$command->set('userid', $data[$x]['userID']);
	$command->set('gender', $data[$x]['gender']);
	$command->set('age', $data[$x]['age']);
	$command->set('zipcode', $data[$x]['zipcode']);
	$client_response = $client->execute($command);
}
/*
 foreach ($data as $user) {
	$command = $client->getCommand('create_user', array('pio_uid' => $user['UserID']));
	$command->set('userid', $user['UserID']);
	$command->set('gender', $user['Gender']);
	$command->set('age', $user['Age']);
	$command->set('zipcode', $user['Zip-code']);
	$client_response = $client->execute($command);
 }
*/
/*
 foreach ($data as $book) {
 	$command = $client->getCommand('create_item', array('pio_iid' => $i, 'pio_itypes' => 1));
	$command->set('isbn', $book['ISBN']);
	$command->set('title', $book['Book-Title']);
	$command->set('author', $book['Book-Author']);
	$command->set('year', $book['Year-Of-Publication']);
	$command->set('publisher', $book['Publisher']);
	$command->set('images', $book['Image-URL-S']);
	$command->set('imagem', $book['Image-URL-M']);
	$command->set('imagel', $book['Image-URL-L']);
	$client_response = $client->execute($command);
	$i++;
 }*/


