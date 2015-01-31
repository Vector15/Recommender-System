<?php
// session_start();
// require 'vendor/autoload.php';

// use PredictionIO\PredictionIOClient;

// $client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));


// $rec= array(

// 			'genre1' => array('pio_iid' => array('1', '2', '3')),
// 			'genre2' => array('pio_iid' => array('4', '5', '6')),
// 			'genre3' => array('pio_iid' => array('7', '8', '9')),
// 			'genre4' => array('pio_iid' => array('10', '11', '12'))
// 	);

// $_SESSION['dthree'] = array();
// $_SESSION['dthree']['name']='Username';
// $_SESSION['dthree']['children']= array();
// $i=0;
// 	foreach ($rec as $key => $value) {
// 		$j=0;
// 		$inner_array[$i]['name']= $key;

		
// 		foreach (array_values($value) as $id) {
		
// 			foreach ($id as $no) {
// 				$inner_most_array[$j]['name']=$no;
// 				$j++;	
// 			}
// 		}
// 		$inner_array[$i]['children']=array_values($inner_most_array); 
// 		$i++;
// 	}
// $_SESSION['dthree']['children']=array_values($inner_array);
// echo json_encode($_SESSION['dthree'],JSON_PRETTY_PRINT);

//------------------------------------------------------------------------------------------------------------------------



// $recommended= $_SESSION['backup'];


		
// 	$_SESSION['dthree'] = array();
// $_SESSION['dthree']['name']='Alex Garret';
// $_SESSION['dthree']['children']= array();
// $i=0;
// 	foreach ($recommended as $key => $value) {
// 		$j=0;
// 		$inner_array[$i]['name']= $key;

		
// 		foreach (array_values($value['pio_iids']) as $id) {
		
// 			$command = $client->getCommand('get_item', array('pio_iid' => $id));
// 			$movie = $client->execute($command);
// 				$inner_most_array[$j]['name']=$movie['Name'];
// 				$j++;	
			
// 		}
// 		$inner_array[$i]['children']=array_values($inner_most_array); 
// 		$i++;
// 	}
// $_SESSION['dthree']['children']=array_values($inner_array);
// echo json_encode($_SESSION['dthree'],JSON_PRETTY_PRINT);
			
		









?>
