<?php
session_start();

$myusername = "";
$myemail = "";
$score = "";

// if someone is coming to this page without logging in kick them to the login page
if (!isset($_SESSION['myusername'])) {
	header('Location: index.php');
}
else {

$myusername = $_SESSION['myusername'];
$myemail = $_SESSION['myemail'];
$score = $_SESSION['scoreBoard'];

$_SESSION['isFinished'] = 5;

	$host=""; // Host name went here...
	$username=""; // Mysql username went here...
	$password=""; // Mysql password went here...
	$db_name=""; // Database name went here...
	$tbl_name=""; // Table name went here...

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");
	
	// see if this team has already run through the scavenger hunt
	$sql="SELECT * FROM $tbl_name WHERE email='$myemail'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);

	// if the email is not already in the results table it is their first time through, write their results to SQL
	if($count==0){
	
		$sqlInsert = "INSERT INTO results2 (fullname, email, score) VALUES ('" . $_SESSION['myusername'] . "','" . $_SESSION['myemail'] . "','" . $_SESSION['scoreBoard'] . "')";
	
		mysql_query($sqlInsert) or die("SELECT Error: ".mysql_error());

	}
	else {
		// update their record
		$sqlInsert = "UPDATE results2 SET fullname = '" . $_SESSION['myusername'] . "', email = '" . $_SESSION['myemail'] . "', score = '" . $_SESSION['scoreBoard'] . "' WHERE email='" . $_SESSION['myemail'] . "'";
	
		mysql_query($sqlInsert) or die("SELECT Error: ".mysql_error());
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GEW NU Scavenger Hunt</title>

<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>

select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] { font-size: 16px; }

div {
	border:0px solid;
	border-radius:25px;
	background-color:#FF2E2A;
	box-shadow: 10px 10px 5px #888888;
}

p {
	color:white;
	font:18px helvetica;
	padding:2px 15px;
}

p.addRoom {
	padding:2.5cm;
}

hr {
	width:50%;
	margin-left:auto;
	margin-right:auto;
}
</style>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45872249-1', 'gewnuhunt.com');
  ga('send', 'pageview');

</script>

</head>

<body>

	<div id="page">

			<center>
        
        	<p> </p>
        	        	<p> <img src="images/demo_day_logo.png" /> </p>
            <hr />
            
        	<p id="congrats"> Congratulations! You've been entered into the Global E-Week Raffle to win an iPod Nano, gift certificate to Fogo de Chao and more! Check in at NEXPO tomorrow night at Cabot Athletic Facility before 7pm and stick around for the winner announcements to claim your prize. </p>
            <!--<p id="finishTime"> Your finish time is: <? #echo $_SESSION['finishTime'] ?> seconds </p>-->
            
            <hr  />
            <p><img src="images/gew_logo.png" align=CENTER /> </p>
            <p class="addRoom"> </p>
            
            </center>

  </div>
</body>
</html>


