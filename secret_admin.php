<?php
session_start();

// if someone is coming to this page without logging in kick them to the login page
if (!isset($_SESSION['myusername'])) {
	header('Location: admin_login.php');
}

	global $loginErr;
	
	$host=""; // Host name went here...
	$username=""; // Mysql username went here...
	$password=""; // Mysql password went here...
	$db_name=""; // Database name went here...
	$tbl_name=""; // Table name went here...

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	// Print out our table with the highest scores at the top to pick winners for prizes.
	$result = mysql_query( "SELECT * FROM results2 ORDER BY score DESC;" )
    or die("SELECT Error: ".mysql_error());
    $num_rows = mysql_num_rows($result);
    print "There are $num_rows records.<P>";
    print "<table width=200 height=100 border=1>\n";
    while ($get_info = mysql_fetch_row($result)){
    print "<tr>\n";
    foreach ($get_info as $field)
    print "\t<td><font face=arial size=2/>$field</font></td>\n";
    print "</tr>\n";
    }
    print "</table>\n";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GEW NU Scavenger Hunt Admin</title>

<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>

select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] { font-size: 16px; }

div {
	border:0px solid;
	border-radius:25px;
	background-color:#079BC9;
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
</style>

</head>

<body>

</body>
</html>


