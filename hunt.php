<?php

date_default_timezone_set('US/Eastern');

session_start();

// if someone is coming to this page without logging in kick them to the login page
if (!isset($_SESSION['myusername'])) {
	header('Location: index.php');
}

// if we have already finished and are trying to go back... cheating!
if($_SESSION['isFinished'] == 5) {
	header('Location: congrats.php');
}

$host=""; // Host name went here...
$username=""; // Mysql username went here...
$password=""; // Mysql password went here...
$db_name=""; // Database name went here...
$tbl_name=""; // Table name went here...

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

// Our questions array as provided by Max
$questions = array();
$questions[0] = "The website to help intermediate musicians to learn more efficiently is _____?";
$questions[1] = "The company that makes an affordable electric longboard kit is _____?";
$questions[2] = "The online service that allows you to efficiently give PowerPoint presentations is _____?";
$questions[3] = "The company that provides online cellphone rental service is _____?";
$questions[4] = "The photographer search engine is _____?";
$questions[5] = "The college party planning app is _____?";
$questions[6] = "The company that provides pre-measured and packaged recipes for students or families is _____?";
$questions[7] = "The gamified running app is _____?";
$questions[8] = "The company that sells customized nutritional drinks is _____?";
$questions[9] = "The company that provides personal privacy accessories for consumer electronics is _____?";
$questions[10] = "The company that is Airbnb for storage is _____?";
$questions[11] = "The specialized lock for bicycles is _____?";
$questions[12] = "The mobile app assistant for working out is _____?";
$questions[13] = "The company that uses the waste from energy generation is _____?";
$questions[14] = "The mobile app that allows music requests is _____?";
$questions[15] = "The app that finds you an open parking spot is _____?";
$questions[16] = "The suit rental service is _____?";
$questions[17] = "The European sandwich delivery service is _____?";

// Our answers array as provided by Max
$questionAnswers = array();
$questionAnswers[0] = "unscrambled melody";
$questionAnswers[1] = "dash";
$questionAnswers[2] = "qprez";
$questionAnswers[3] = "phone-a-friend";
$questionAnswers[4] = "picme";
$questionAnswers[5] = "toga";
$questionAnswers[6] = "eatonomous";
$questionAnswers[7] = "emphariq";
$questionAnswers[8] = "fitplus";
$questionAnswers[9] = "secureflip";
$questionAnswers[10] = "stowaway";
$questionAnswers[11] = "locktopus";
$questionAnswers[12] = "spotter";
$questionAnswers[13] = "poop power";
$questionAnswers[14] = "jukke";
$questionAnswers[15] = "curveview";
$questionAnswers[16] = "suit yourself";
$questionAnswers[17] = "miga";

$answeredNumbers = array(); // what question numbers have we answered successfully
$textInput = ""; // holds the user's answer to question
$score = 0; // how many have we gotten right so far
$totalAnswers = 18; // holds how many answers we have
$answerToGuess = ""; // what answer are we trying to guess at the moment
$startD = ""; // start time
$stopD = ""; // stop time
$endD = ""; // holds stopD - startD
$msgArea = ""; // for messages

// for saving the array of answered question numbers as a string with a : delimiter	between
// questions
$asString = "";


// Obtain $_Session data if we already have it. Otherwise, we're starting fresh!

// have we already answered some questions? Which ones? This one is for writing to SQL as a string.
if(!isset($_SESSION['asString'])) {
	$_SESSION['asString'] = $asString;
}
else {
	$asString = $_SESSION['asString'];
}

// have we already answered questions? Which ones? This one is not for writing to SQL.
if(!isset($_SESSION['answeredQuestionsSet'])) {
	$_SESSION['answeredQuestionsSet'] = $answeredNumbers;
}
else {
	$answeredNumbers = $_SESSION['answeredQuestionsSet'];
}


// have we already started the timer? No longer in use since it was requested to change from a 
// Scavenger Hunt to a Q&A.
if(!isset($_SESSION['startDateAcquired'])) {
	$startD = getdate();
	$_SESSION['startDateAcquired'] = $startD;
}
else {
	$startD = $_SESSION['startDateAcquired'];
}


// have we already set the question?
if(!isset($_SESSION['questionSet'])) {
	$answerToGuess = (int)(mt_rand(1,$totalAnswers)) - 1;
	$_SESSION['questionSet'] = $answerToGuess;
}
else {
	$answerToGuess = $_SESSION['questionSet'];
}

// have we already started the timer? No longer in use since it was requested to change from a 
// Scavenger Hunt to a Q&A.
if(!isset($_SESSION['scoreBoard'])) {
	$score = 0;
	$_SESSION['scoreBoard'] = $score;
}
else {
	$score = $_SESSION['scoreBoard'];
}

// if we have posted an answer
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$textInput = test_input($_POST["guess"]);
	# echo $textInput;
  
  	compareGuess();
  	
}

// This function compares the user's guess to the actual answer.
// Pre: Given a guess from user input.
// Post: Either increase the score of the user if they got the question right, tell them to try again and supply the
// same question, or if they have now answered all the questions correctly, forward them on to the congrats page.
function compareGuess()
{
	// use these global variables
	global $textInput, $questionAnswers, $answeredNumbers, $answerToGuess, $msgArea, $score, $totalAnswers, $stopD;
	global $asString;
	
	// if we get the right passcode-- the || statement is a kluge in case someone types the 
	// company name 'phone-a-friend' without the dashes. More here to prevent frustration for the user.
	if($textInput == $questionAnswers[$answerToGuess] || (($answerToGuess == 3) && $textInput == 'phone a friend')) {
		$msgArea = "That's right!";
		$textInput = ""; // reset this
		
		// add our answer to guess into our answered array
		$answeredNumbers[$score] = $answerToGuess;	
		$_SESSION['answeredQuestionsSet'] = $answeredNumbers;
	
		// build our array as a string so it can be easily stored in SQL
		$asString .= "$answerToGuess";
		$asString .= ":";
		$_SESSION['asString'] = $asString;
		# echo $asString;
		
		// explode our string into an array again
		$explodedArray = array();
		# print_r(explode(':',$asString,-1));
		$explodedArray = explode(':',$asString,-1);
				
		// increase score and see if we have answered all the questions 
		$score++;
		
		$sqlInsert = "UPDATE results2 SET fullname = '" . $_SESSION['myusername'] . "', email = '" . $_SESSION['myemail'] . "', score = '" . $score . "' WHERE email='" . $_SESSION['myemail'] . "'";
	
		mysql_query($sqlInsert) or die("SELECT Error: ".mysql_error());
		
		if ($score == $totalAnswers) {
			// set our stop date
			$stopD = getdate();
			
			// compute the final time
			computeFinalTime();
			
			// redirect to a congratulations page with final time
			header('Location: congrats.php');
		}
		// if not at the end of questions, find a question we haven't answered at random
		else if($score != $totalAnswers) {
			// pick a new question number at random
			$answerToGuess = (int)(mt_rand(1,$totalAnswers)) - 1;
			// try and find it in the array
			$a = in_array($answerToGuess, $explodedArray);
			
			// until you cannot find it in the array keep generating a new random question to answer and test
			while ($a !== FALSE) {
				$answerToGuess = (int)(mt_rand(1,$totalAnswers)) - 1;
				$a = in_array($answerToGuess, $explodedArray);
			}

		}
		// update what hint we show
		$_SESSION['questionSet'] = $answerToGuess;
		
		// update what the score is	
		$_SESSION['scoreBoard'] = $score;
	}

	// the guess was wrong
	else {
			$msgArea = "Try again!";
	}

}

// to prevent SQL injection attacks and normalize (again overkill but...)
function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
	 $data = strtolower($data);
     return $data;
}

// This function will find how long in seconds it took to complete the hunt.
// We ditched this functionality when it was decided to make this a Q&A web app instead
// of the original scavanger hunt.
function computeFinalTime ()
{
	global $startD, $stopD, $endD;
	
	$endD = $stopD[0] - $startD[0];
	$_SESSION['finishTime'] = $endD;
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
            
    		<form name="form2" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  
            <p id="hintArea"> Question: <? echo $questions[$answerToGuess]; ?> </p>
            <p id="answerArea"> Enter your answer: <br /><input type="text" name="guess" id="guess" /> <br /><input type="submit" value="Try Guess" /> </p>
		   	</form>
            
        	<p id="messageArea"> <span class="error"><b><font color="black"> <?php echo $msgArea;?> </font></b></span></p>
            <p id="questionsLeft"> Questions Left: <? echo ($totalAnswers-$score); ?> </p>
            
        	<!-- <p id="scoreBoard"> Score: <? #echo $score; ?> </p>
            
        	<p id="clockStart"> Clock started at: <? # echo $startD[hours] . ":" . $startD[minutes] . ":" . $startD[seconds]; ?> </p> 
        	<p id="clockStop"> Clock stopped at:  <? # echo $stopD[hours] . ":" . $stopD[minutes] . ":" . $stopD[seconds]; ?> </p>
        	<p id="finishTime"> Finish time: <? # echo $endD ?></p> -->
            
            <hr  />
            <p><img src="images/gew_logo.png" align=CENTER /> </p>
            <p class="addRoom"> </p>
            
            </center>

  </div>
</body>
</html>