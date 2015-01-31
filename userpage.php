<?php
session_start();
require 'vendor/autoload.php';

use PredictionIO\PredictionIOClient;

$client = PredictionIOClient::factory(array("appkey" => "kqtMzyrFEbMnQmhwS7Tg87sv0hMr8uvFkqK2SyM2rJpk9EUh4COScQSXWLj36S4O"));

//Select Random User

$offset = rand(1, 943);
$offset=944;
//echo "Offset: $offset";
$uid = ''.$offset;
//echo "UID: $uid";

if( (!isset($_SESSION['fac_id'])) || empty($_SESSION['fac_id'])){
die('Sorry, Wrong Request !');
}


if( (!isset($_SESSION['fac_name'])) || empty($_SESSION['fac_name'])){
die('Sorry, Wrong Request !');
}

if((isset($_SESSION['user_id'])) && !empty($_SESSION['user_id'])){

$uid=$_SESSION['user_id'];
}else{

$_SESSION['user_id']=$uid;
}

$image = 'https://graph.facebook.com/'.$_SESSION['fac_id'].'/picture?width=300';


//echo 'Session:'.$_SESSION['user_id'];

//Get User Details
$command = $client->getCommand('get_user', array('pio_uid' => $uid));
$response = $client->execute($command);



$m = new MongoClient();
$db = $m->selectDB('predictionio_appdata');
$actions = new MongoCollection($db, 'u2iActions');
$uido = '1_'.$uid;
$cursor = $actions->find(array('action' => 'rate','uid' => $uido), array('uid','iid','v'))->limit(6);
$data = array_values(iterator_to_array($cursor));
$data= array_reverse($data);
$user_genere_list = [];
//Get User Ratings

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
	<link rel="stylesheet" type="text/css" href="TableCSSCode.css">
<style>	
	#logo a {
color: #909090;
margin-left: 0px;
margin-right: 0;
}

tr{
	height: 10px;
	text-align: center;
	}
</style>

	
	

	
</head>
<body style="background:black">
	<div class="main-container">
		<nav class="main-nav">
			<div id="logo" class="left"><a href="#" style="font-weight: 500; color: #1D2D5F;">Welcome,<?php echo $_SESSION['fac_name'] ?> </a></div>
			<ul class="nav right center-text">
				
				<li class="btn"><a href="http://localhost:88/moviepre/predict.php">Recommend Movies</a></li>
								
				
				
			</ul>
		</nav>
		<div class="content-container">
		<?php
	echo '
			<header>
				<h1 class="center-text" style="font-weight: bolder;
color: #2D245A; font-size: 37px;">User Profile</h1>
			</header>
			<br>
		<div id="portfolio-content" class="center-text">


				<div class="CSSTableGenerator" style="width:300px; margin:auto;">
				
				<table style="table-layout:fixed">
                   
				   <tr>
				
				 
				   <td>
				  <img src="'.$image.'" alt="'.$_SESSION['fac_name'].'" class="img-thumbnail">
				   </td>
				 </tr>
				  
				   
		      </table>
				
				</div>
				<br>

				<div class="CSSTableGenerator" style="width:500px;margin:auto;">
				
				<table style="table-layout:fixed; width:500px;margin:auto;">
				<tr> 
				   <td>
				   User Name
				   </td>
				   
				   
				   <td>
				   '.$_SESSION['fac_name'].' 
				   </td>
				   </tr>
				   
				   
				   
				   
				   
				   

				 <tr>
				 <td>
				 Unique ID
				 </td>
				   <td>
				   '.$response['pio_uid'].'
				   </td>
				   </tr>
				   <tr>

				   <td>
				   Gender
				   </td>
				   <td>
				   '.$response['age'].'
				   </td>
				 </tr>

				 <tr>
				 <td>
				   Age
				   </td>
				   <td>
				   '.$response['gender'].'
				   </td>
				   </tr>

				</table>

				</div>
				

			
			<div class="portfolio-page" id="page-1">
			<br> <br> <br> <hr/>
			<header>
				<h1 class="center-text" style="font-weight: bolder;
color: #2D245A;font-size:37px; ">User Rating History</h1>
			</header>

			';


try{
	 $test =array();

	foreach ($data as $id) {
		//var_dump($id);
		$iid = substr($id['iid'], strpos($id['iid'], '_') + 1);
		if(@in_array($iid, $test)){
			continue;
		}
		array_push($test,$iid);
		$command = $client->getCommand('get_item', array('pio_iid' => $iid));
		$movie = $client->execute($command);
		
		if(!isset($_SESSION['unseen'])){
			$_SESSION['unseen'] = array();
		}		

		array_push($_SESSION['unseen'], $movie['Name']);


			echo '<div class="portfolio-group">
					<a class="portfolio-item" href="#">
						<img src="'.$movie['url'].'" alt="image 8">
						<div class="detail">

							<h3 style="text-weight:bolder; font-size:20px; font-family: "Lucida Console", Monaco, monospace">'.$movie['Name'].'</h3>';
							foreach($movie['pio_itypes'] as $genre){
			$user_genere_list[] = $genre;
			echo "$genre ";
		}
							echo '<p style="color:green;">Movie Year-'.$movie['Year'].'<br>Critics rating - '.$movie['crating'].'/10 <br>
							Your rating - '.$id['v'].'/5 <br>
							</p>
							
						</div>
					</a>				
				</div>
			';
}


}catch(Exception $e){
		    echo 'Caught exception: ', $e->getMessage(), "\n";
}

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