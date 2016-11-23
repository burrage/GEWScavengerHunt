<?php
session_start();

// define variables and set to empty values
$loginErr = "";
$myusername = $mypassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

   if (empty($_POST["myusername"]))
     {$nameErr = "Admin is required\n";}
   else
     {$myusername = test_input($_POST["myusername"]);}
  
   if (empty($_POST["mypassword"]))
     {$passErr = "Password is required";}
   else
     {$mypassword = test_input($_POST["mypassword"]);}
  
  	checklogin($myusername, $mypassword);
}

// to prevent SQL injection attacks
function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}

// Function to see if the user should have access to the admin page.
// Pre: Passed a sanitized username and password to interact with SQL database.
// Post: If authorized, forward them to the admin page, otherwise they're staying here.
function checklogin($myusername, $mypassword)
{

	global $loginErr;
	
	$host=""; // Host name went here...
	$username=""; // Mysql username went here...
	$password=""; // Mysql password went here...
	$db_name=""; // Database name went here...
	$tbl_name=""; // Table name went here...

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");


	// To protect MySQL injection (overkill considering we did this before but you know...)
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);

	$sql="SELECT * FROM $tbl_name WHERE admin='$myusername' and pass='$mypassword'";
	$result=mysql_query($sql);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1){

		// Register $myusername and redirect to file "hunt.php"
		$_SESSION['myusername'] = $myusername;
		#echo "Myusername=". $_SESSION['myusername'];
		header('Location: secret_admin.php');
		
	}
	else {
		$loginErr = "Wrong Username or Password\n";
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

</head>

<body>

	<div id="page">

			<center>
        
        	<p> </p>
        	<p> <img src="images/demo_day_logo.png" /> </p>
            <hr />
            
            <form name="form1" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        	<p id="userEntry">Admin Name: <br /><input type="text" name="myusername" id="myusername"> </p>
            <p id="password"> Password: <br /><input type="password" name="mypassword" id="mypassword" /> </p>
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
