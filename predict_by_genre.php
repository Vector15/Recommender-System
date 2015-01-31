<?php
session_start();
require 'vendor/autoload.php';

use PredictionIO\PredictionIOClient;

$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));




$recommended = [];
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
							<li class="btn"><a href="http://localhost:88/moviepre/predict.php">General</a></li>
				<li class="btn"><a rel="nofollow" href="http://localhost:88/moviepre/d3.php">Visualization</a></li>
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

		if(isset($_SESSION['copyarr'])){

			$recommended= $_SESSION['copyarr'];
		}

		
		
		foreach ($recommended as $genre => $rec) {



	$recommended_id = $rec['pio_iids'];
	
	echo '
			<header>
				<h1 class="center-text" style="font-weight: bolder;color: #2D245A; font-size: 37px;">Movie recommendations for you in genre : '.$genre.'</h1>
			</header>
		<div id="portfolio-content" class="center-text">
			<div class="portfolio-page" id="page-1">';
	
	foreach ($recommended_id as $id) {
		$command = $client->getCommand('get_item', array('pio_iid' => $id));
			$movie = $client->execute($command);



			if(! isset($_SESSION['avoid_repeat2'])) {
		 $_SESSION['avoid_repeat2']= array();
		}
	
			

				if(in_array($movie['Name'], $_SESSION['avoid_repeat2'])){
					continue;
				}

				array_push($_SESSION['avoid_repeat2'], $movie['Name']);

				if(in_array($movie['Name'], $_SESSION['unseen'])){
					continue;
				}


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
	
	}
	
}

$_SESSION['avoid_repeat2']=array();

		?>
		</div>
		<footer>
			<p>Copyright &copy;Abhilash Abishek Mayur <!-- Credit: www.templatemo.com --></p>
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
