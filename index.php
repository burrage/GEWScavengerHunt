<?php
session_start();

// define variables and initialize
$loginErr = "";
$myusername = $myemail = "";

// if we have already finished and are trying to go back to boost our score... cheating!
if($_SESSION['isFinished'] == 5) {
	header('Location: congrats.php');
}

// has the user filled out everything to start the web app?
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

   if (empty($_POST["myusername"]))
     {$nameErr = "Full Name is required\n";}
   else
     {$myusername = test_input($_POST["myusername"]);}
  
   if (empty($_POST["myemail"]))
     {$passErr = "E-mail is required";}
   else
     {$myemail = test_input($_POST["myemail"]);}
  
  // see if this user has already started the scavenger hunt
  	checklogin($myusername, $myemail);
}


// The following function is to prevent SQL injection attacks.
// Pre: Passed the user input.
// Post: Sanitized user input for interacting with the SQL database.
function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}


// The following function checks to see if the user has already accessed the web app and will redirect them to the
// web app if not.
// Pre: Passed a sanitized username and e-mail to interact with the SQL database.
// Post: Either inform the user that they have already entered the contest (they should not be able to enter multiple times)
// or register their information and send them along to the web app.
function checklogin($myusername, $myemail)
{

	global $loginErr;
	
	$host=""; // Host name went here...
	$username=""; // Mysql username went here...
	$password=""; // Mysql password went here...
	$db_name=""; // Database name went here...
	$tbl_name=""; // Table name went here...

	// Connect to server and select databse.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	// To protect MySQL injection (overkill considering we did this before but you know... extra safe!)
	$myusername = stripslashes($myusername);
	$myemail = stripslashes($myemail);
	$myusername = mysql_real_escape_string($myusername);
	$myemail = mysql_real_escape_string($myemail);

	$sql="SELECT * FROM $tbl_name WHERE email='$myemail'";
	$result=mysql_query($sql);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

	// If no result matched $myusername or $myemail, table row must be 0 row
	if($count==1){
			$loginErr = "You have already entered the contest.\n";		
	}
	// Register $myusername and redirect to file "hunt.php"
	else {
		$_SESSION['myusername'] = $myusername;
		$_SESSION['myemail'] = $myemail;
		
		// write them to our database file
		$sqlInsert = "INSERT INTO results2 (fullname, email, score) VALUES ('" . $_SESSION['myusername'] . "','" . $_SESSION['myemail'] . "', '0')";
		mysql_query($sqlInsert) or die("SELECT Error: ".mysql_error());
		#echo "Myusername=". $_SESSION['myusername'];
		header('Location: hunt.php');
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
            
            <form name="form1" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        	<p id="userEntry">Full name: <br /><input type="text" name="myusername" id="myusername"> </p>
            <p id="email"> E-mail: <br /><input type="email" name="myemail" id="myemail" /> </p>
            <span class="error"><b><font color="black"> <?php echo $loginErr;?> </font></b></span>
            <p id="submitButton"> <input type="submit" value="Submit" /> </p>
            </form>  
    

            <hr  />
            <p><img src="images/gew_logo.png" align=CENTER /> </p>
            <p class="addRoom"> </p>
            
            </center>

  </div>
</body>
</html>


