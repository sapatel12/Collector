<?php
/*	Collector
	A program for running experiments on the web
	Copyright 2012-2014 Mikey Garcia & Nate Kornell
 */
	ini_set('auto_detect_line_endings', true);			// fixes problems reading files saved on mac
	session_start();									// start the session at the top of each page
	require 'fileLocations.php';						// sends file to the right place
	require $up.$expFiles.'Settings.php';				// experiment variables
	
	// if someone skipped to done.php without doing all trials
	if ($_SESSION['finishedTrials'] <> TRUE) {
		header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");			// rick roll
		exit;
	}
	
	if ($_SESSION['Debug'] == FALSE) {
		error_reporting(0);
	}
	require 'CustomFunctions.php';						// Loads all of my custom PHP functions
	
	#### TO-DO ####
	$finalNotes = '';
	/*
	 * Write code that looks at previous logging in activity and gives recommendations as to whether or not to include someone
	 * ideas:
	 *		if someone has logged in more than once, flag them
	 * 		if someone has 1 login and no ends then say they're likely good
	 * 		if someone already has 1 finish then say so	
	 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/global.css" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Kreon' rel='stylesheet' type='text/css' />
	<title>Done!</title>
</head>
<?php flush(); ?>
<body>	

<?php
	
	if($nextExperiment == FALSE) {
		echo '<div id="donePage">
				<h2>Thank you for your participation!</h2>
				<p>If you have any questions about the experiment please email '.$experimenterEmail.'</p>
			  </div>';
		if ($mTurkMode == TRUE) {
			echo '<div id="donePage"> <h3>Your verification code is: '.$verification.'</h3></div>';
		}
	} else {
		echo "<h2>Experiment will resume in 5 seconds.</h2>";
		$nextLink = 'http://'.$nextExperiment;
		$username = $_SESSION['Debug'] ? $debugName.' '.$_SESSION['Username'] : $_SESSION['Username'];
		echo '<meta http-equiv="refresh" content="5; url='.$nextLink.'Code/login.php?Username='.urlencode($username).'&Condition=Auto&ID='.$_SESSION['ID'].'">';
	}
	
		
	// readable($_SESSION['Trials']);
?>

<?php
	$duration = time() - strtotime( $_SESSION['Start Time'] );
	$hours = floor( $duration/3600 );
	$minutes = floor( ($duration - $hours*3600)/60 );
	$seconds = $duration - $hours*3600 - $minutes*60;
	if( $hours < 10 AND $hours > 0 ) { $hours = '0'.$hours.':'; } elseif( $hours == 0 ) { $hours = ''; } else { $hours = $hours.':'; }
	if( $minutes < 10 ) { $minutes = '0'.$minutes; }
	if( $seconds < 10 ) { $seconds = '0'.$seconds; }
	$duration = $hours.$minutes.':'.$seconds;
	#### Record info about the person ending the experiment to status finish file
	$data = array(
						'Username' 					=> $_SESSION['Username'],
						'ID' 						=> $_SESSION['ID'],
						'Date' 						=> date('c'),
						'Duration' 					=> $duration,
						'Session' 					=> $_SESSION['Session'],
						'Condition #'				=> $_SESSION['Condition']['Number'],
						'Inclusion Notes' 			=> $finalNotes
					 );
	arrayToLine($data, $up.$dataF.$_SESSION['DataSubFolder'].$extraDataF.$statusEndFileName.$outExt);
	########
	
	$_SESSION = array();											// clear out all session info
	session_destroy();												// destroy the session so it doesn't interfere with any future experiments
	
?>
	<script src="http://code.jquery.com/jquery-1.8.0.min.js" type="text/javascript"> </script>
	<script src="javascript/jsCode.js" type="text/javascript"> </script>
</body>
</html>