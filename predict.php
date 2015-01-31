<?php
ini_set('max_execution_time', 360);
session_start();
require 'vendor/autoload.php';

use PredictionIO\PredictionIOClient;

$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));



$offset = rand(1, 943);
$uid = ''.$offset;
if((isset($_SESSION['user_id'])) && !empty($_SESSION['user_id'])){
$uid=$_SESSION['user_id'];
}

$command = $client->getCommand('get_user', array('pio_uid' => $uid));
$response = $client->execute($command);


$m = new MongoClient();
$db = $m->selectDB('predictionio_appdata');
$actions = new MongoCollection($db, 'u2iActions');
$uido = '1_'.$uid;
$cursor = $actions->find(array('action' => 'rate','uid' => $uido), array('uid','iid','v'))->limit(5);
$data = array_values(iterator_to_array($cursor));

$user_genere_list = [];

try{
	

	foreach ($data as $id) {
		//var_dump($id);
		$iid = substr($id['iid'], strpos($id['iid'], '_') + 1);
		$command = $client->getCommand('get_item', array('pio_iid' => $iid));
		$movie = $client->execute($command);
	
		foreach($movie['pio_itypes'] as $genre){
			$user_genere_list[] = $genre;
			
		}

	}
}
catch(Exception $e){
		    echo 'Caught exception: ', $e->getMessage(), "\n";
}
$recommended = [];
try{
	$client->identify($uid);
	foreach ($user_genere_list as $genre) {
		$command = $client->getCommand('itemrec_get_top_n', array('pio_engine' => 'Movie_Engine' ,'pio_itypes' => $genre,'pio_n' => 4));
		$recommended[$genre] = $client->execute($command);
	}

}catch(Exception $e){
		    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>

<!DOCTYPE html>

<head>
	<title>Recommenders</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="css/magnific-popup.css" rel="stylesheet"> 
	<link href="css/templatemo_style.css" rel="stylesheet" type="text/css">	
<style>	

	#logo a {
color: #909090;
margin-left: 0px;
margin-right: 0;
}
</style>
	
</head>
<body style="background:black;">
	<div class="main-container">
		<nav class="main-nav">
			<div id="logo" class="left"><a href="#">Movies You Might Like ! </a></div>
			<ul class="nav right center-text">
				
				<li class="btn"><a href="userpage.php">My ratings</a></li>
				<li class="btn"><a href="http://localhost:88/moviepre/predict_by_genre.php">Show By Genre</a></li>				
				<li class="btn"><a rel="nofollow" href="http://localhost:88/moviepre/d3.php">Visualization</a></li>
				<li class="btn"><a href="contact.php">Feedback</a></li>
			</ul>
		</nav>
		<div class="content-container">
		<?php
	
		if(! isset($_SESSION['backup'])){
			$_SESSION['backup']= array();
			$_SESSION['backup']= $recommended;
		}else{
			$recommended=$_SESSION['backup'];
		}


		if(! isset($_SESSION['copyarr']) ) {
			$_SESSION['copyarr']= array();
			$_SESSION['copyarr']= $recommended;
		}else{
			$_SESSION['copyarr'] = $recommended;
		}

		echo '<header>
		These are movies from various genres that you might like.
	</header>';	

$_SESSION['dthree'] = array();
$_SESSION['dthree']['name']='Alex Garret';
$_SESSION['dthree']['children']= array();
$i=0;
	foreach ($recommended as $key => $value) {
		$j=0;
		$inner_array[$i]['name']= $key;

		
		foreach (array_values($value['pio_iids']) as $id) {
		
			$command = $client->getCommand('get_item', array('pio_iid' => $id));
			$movie = $client->execute($command);
				$inner_most_array[$j]['name']=$movie['Name'];
				$j++;	
			
		}
		$inner_array[$i]['children']=array_values($inner_most_array); 
		$i++;
	}
	$_SESSION['dthree']['children']=array_values($inner_array);




foreach ($recommended as $genre => $rec) {

	$recommended_id = $rec['pio_iids'];
	//Item Details
	echo ' 
			
		<div id="portfolio-content" class="center-text">
			<div class="portfolio-page" id="page-1">';
	
	foreach ($recommended_id as $id) {
		$command = $client->getCommand('get_item', array('pio_iid' => $id));
			$movie = $client->execute($command);



			if(! isset($_SESSION['avoid_repeat'])) {
		 $_SESSION['avoid_repeat']= array();
		}
	
			

				if(in_array($movie['Name'], $_SESSION['avoid_repeat'])){
					continue;
				}



				array_push($_SESSION['avoid_repeat'], $movie['Name']);



				if(in_array($movie['Name'], $_SESSION['unseen'])){
					continue;
				}


			//movieID|Name|IMDBid|Year|url|rtid|crating|cscore|arating|ascore|genre
			echo '<div class="portfolio-group">
					<a class="portfolio-item" href="#">
						<img src="'.$movie['url'].'" alt="image 8">
						<div class="detail">
							<h3 style="text-weight:bolder; font-size:20px;font-family: "Lucida Console", Monaco, monospace">'.$movie['Name'].'</h3>
							<p style="font-weight: bolder">'.$genre.'</p>
							<p  style="color:green"> Critics rating - '.$movie['crating'].'/10 <br>
							Audience rating - '.$movie['arating'].'/5 <br>
							</p>
							
						</div>
					</a>				
				</div>
			';
			

	}
	
}


$_SESSION['avoid_repeat']=array();

		?>
		</div>
		<footer>
			<p>Copyright &copy;Abhilash Abishek Mayur</p>
			<div class="social right">
				<a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a>
				<a href="https://www.twitter.com"><i class="fa fa-twitter"></i></a>
				<a href="https://www.plus.google.com"><i class="fa fa-google-plus"></i></a>
				
			</div>
		</footer>
	</div>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.magnific-popup.min.js"></script> 
	<script type="text/javascript" src="js/templatemo_script.js"></script>
		
</body>
</html>
