<?php
session_start();
require 'vendor/autoload.php';

use PredictionIO\PredictionIOClient;

$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));

//Select Random User

$offset = rand(1, 943);
$uid = ''.$offset;
if((isset($_SESSION['user_id'])) && !empty($_SESSION['user_id'])){
$uid=$_SESSION['user_id'];
}
//echo 'Session:'.$_SESSION['user_id'].'<br>';
//echo "UID: $uid";
//Get User Details
$command = $client->getCommand('get_user', array('pio_uid' => $uid));
$response = $client->execute($command);


// //UserID|Gender|Age|Occupation|Zip-code
// echo "<h1>User Details</h1>";
// echo "<ul>";
// echo "<li>UserID :".$response['pio_uid']."</li>";
// echo "<li>Age :".$response['gender']."</li>";
// echo "<li>Gender :".$response['age']."</li>";
// echo "<li>Zip Code :".$response['zipcode']."</li>";
// echo "<ul>";
// //var_dump($offset);




$m = new MongoClient();
$db = $m->selectDB('predictionio_appdata');
$actions = new MongoCollection($db, 'u2iActions');
$uido = '1_'.$uid;
$cursor = $actions->find(array('action' => 'rate','uid' => $uido), array('uid','iid','v'))->limit(5);
$data = array_values(iterator_to_array($cursor));

$user_genere_list = [];
//Get User Ratings
try{
	//echo "<h2>User Rating History</h2>";

	foreach ($data as $id) {
		//var_dump($id);
		$iid = substr($id['iid'], strpos($id['iid'], '_') + 1);
		$command = $client->getCommand('get_item', array('pio_iid' => $iid));
		$movie = $client->execute($command);
		//movieID|Name|IMDBid|Year|url|rtid|crating|cscore|arating|ascore|genre
		/*echo "<li>";
		echo "<ul>";
		echo "<li> :<img src='".$movie['url']."'/></li>";
		echo "<li>Title : ".$movie['Name']."</li>";
		echo "<li>Genre : ";*/
		foreach($movie['pio_itypes'] as $genre){
			$user_genere_list[] = $genre;
			//echo "<h4>$genre</h4>";
		}
		/*echo "</li>";
		echo "<li>Year : ".$movie['Year']."</li>";
		echo "<li><h3>Critic Score : ".$movie['cscore']."</h3>";
		echo "<li><h3>Audience Score : ".$movie['ascore']."</h3>";
		echo "<li><h3>Critic Rating : ".$movie['crating']."</h3>";
		echo "<li><h3>Audience Rating : ".$movie['arating']."</h3>";
		echo "<li><h3>User Rating : ".$id['v']."</h3>";
		echo "</ul></li>"; */

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
	//$command = $client->getCommand('itemrec_get_top_n', array('pio_engine' => 'movieitemrecomengine' ,'pio_itypes' => $genre,'pio_n' => 10));
	//$recommended = $client->execute($command);
}catch(Exception $e){
		    echo 'Caught exception: ', $e->getMessage(), "\n";
}
//var_dump($recommended);
//foreach ($recommended as $genre => $rec) {



	//$recommended_id = $rec['pio_iids'];
	//Item Details
	//echo "<h1>Recommendations in $genre Genre : </h1>";
	//echo "<ol>";
	//foreach ($recommended_id as $id) {
		//$command = $client->getCommand('get_item', array('pio_iid' => $id));
		//	$movie = $client->execute($command);
			//movieID|Name|IMDBid|Year|url|rtid|crating|cscore|arating|ascore|genre
			//echo "<li>";
			//echo "<ul>";
			//echo "<li> :<img src='".$movie['url']."'/></li>";
			//echo "<li>Title : ".$movie['Name']."</li>";
			//echo "<li>Genre : ";
			//foreach($movie['pio_itypes'] as $genre){
				//echo "<h4>$genre</h4>";
			//}
			//echo "</li>";
			//echo "<li>Year : ".$movie['Year']."</li>";
			//echo "<li><h3>Critic Score : ".$movie['cscore']."</h3>";
			//echo "<li><h3>Audience Score : ".$movie['ascore']."</h3>";
			//echo "<li><h3>Critic Rating : ".$movie['crating']."</h3>";
			//echo "<li><h3>Audience Rating : ".$movie['arating']."</h3>";
			//echo "</ul></li>"; 

	//}
	//echo "</ol>";
//}
//?>

<!DOCTYPE html>
<!-- templatemo 413 flip turn -->
<!-- 
Flip Turn Template 
http://www.templatemo.com/preview/templatemo_413_flip_turn
-->
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
				<li class="btn"><a href="#">My Favourites</a></li>				
				<li class="btn"><a rel="nofollow" href="#">My Stats</a></li>
				<li class="btn"><a href="contact.php">Feedback</a></li>
			</ul>
		</nav>
		<div class="content-container">
		<?php
	
		// $new_recommended= [];
		// $i=0;
		// foreach ($recommended as $genre => $rec) {
		// 	$new_recommended[i]=$genre;
			
		// 	$new_recommended_ids=$rec['pio_iids'];
		// 	$j=0;
		// 	foreach ($new_recommended_ids as $key) {
		// 		$command = $client->getCommand('get_item', array('pio_iid' => $key));
		// 	$movie1 = $client->execute($command);
		// 		$new_recommended[i][j]=$movie1['Name'];
		// 		$j++;
		// 	}
		// 	$i++;
		// }

		// var_dump($new_recommended);
		
		foreach ($recommended as $genre => $rec) {



	$recommended_id = $rec['pio_iids'];
	//Item Details
	echo '
			<header>
				<h1 class="center-text" style="font-weight: bolder;color: #2D245A; font-size: 37px;">Movie recommendations for you in genre : '.$genre.'</h1>
			</header>
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



			//movieID|Name|IMDBid|Year|url|rtid|crating|cscore|arating|ascore|genre
			echo '<div class="portfolio-group">
					<a class="portfolio-item" href="#">
						<img src="'.$movie['url'].'" alt="image 8">
						<div class="detail">
							<h3 style="text-weight:bolder; font-size:20px;font-family: "Lucida Console", Monaco, monospace">'.$movie['Name'].'</h3>
							<p style="color:green">Critics rating - '.$movie['crating'].'/10 <br>
							Audience rating - '.$movie['arating'].'/5 <br>
							</p>
							
						</div>
					</a>				
				</div>
			';
			// echo "<li>";
			// echo "<ul>";
			// echo "<li> :<img src='".$movie['url']."'/></li>";
			// echo "<li>Title : ".$movie['Name']."</li>";
			// echo "<li>Genre : ";
			// foreach($movie['pio_itypes'] as $genre){
			// 	echo "<h4>$genre</h4>";
			// }
			// echo "</li>";
			// echo "<li>Year : ".$movie['Year']."</li>";
			// echo "<li><h3>Critic Score : ".$movie['cscore']."</h3>";
			// echo "<li><h3>Audience Score : ".$movie['ascore']."</h3>";
			// echo "<li><h3>Critic Rating : ".$movie['crating']."</h3>";
			// echo "<li><h3>Audience Rating : ".$movie['arating']."</h3>";
			// echo "</ul></li>"; 

	}
	
}



		?>
		</div>
		<footer>
			<p>Copyright &copy;Abhilash Abishek Mayur <!-- Credit: www.templatemo.com --></p>
			<div class="social right">
				<a href="www.facebook.com"><i class="fa fa-facebook"></i></a>
				<a href="www.twitter.com"><i class="fa fa-twitter"></i></a>
				<a href="www.plus.google.com"><i class="fa fa-google-plus"></i></a>
				
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