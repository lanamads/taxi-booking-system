<?php
//File: booking.php
//Student: 19079062 scc1338
//This file responds to the create booking request and inserts the bookings into the database, returning a confirmation message with a reference to the booking

	// Include database connection settings
	require_once("../../files/settings.php");

	// Establish connection to the database
	$conn = mysqli_connect($host, $user, $pswd, $dbnm);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// Sql query to create table to store bookings if doesn't exist
	$sqlString = "CREATE table IF NOT EXISTS bookings 
		(bkref VARCHAR(8) PRIMARY KEY,
		cname VARCHAR(50) NOT NULL,
		phone VARCHAR(12) NOT NULL,
		unumber VARCHAR(10),
		snumber VARCHAR(10) NOT NULL,
		stname VARCHAR(30) NOT NULL,
		sbname VARCHAR(30),
		dsbname VARCHAR(30),
		date DATE NOT NULL,
		time TIME NOT NULL,
		status VARCHAR(30) DEFAULT 'unassigned',
		generated_bkdate DATE NOT NULL,
		generated_bktime TIME NOT NULL)";

	$result = mysqli_query($conn, $sqlString) or die('Unable to execute the query');

	// Function to generate a unique booking reference number
	function generateBookingRef($conn) {
		$prefix = "BRN";
		$query = "SELECT bkref FROM bookings ORDER BY bkref DESC LIMIT 1";
		$result = mysqli_query($conn, $query);
    
		if ($result && mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$lastRef = substr($row['bkref'], 3); // Remove the prefix
			$newRef = intval($lastRef) + 1;
			$newRef = str_pad($newRef, 5, '0', STR_PAD_LEFT); // Pad with leading zeros
		} else {
			$newRef = '00001'; // Starting value if no records exist
		}
    
		return $prefix . $newRef;
	}

	// Generate the booking reference number
	$bkref = generateBookingRef($conn);

	// Get inputs passed from client
	$cname = mysqli_real_escape_string($conn, $_POST['cname']);
	$phone = mysqli_real_escape_string($conn, $_POST['phone']);
	$unumber = mysqli_real_escape_string($conn, $_POST['unumber']);
	$snumber = mysqli_real_escape_string($conn, $_POST['snumber']);
	$stname = mysqli_real_escape_string($conn, $_POST['stname']);
	$sbname = mysqli_real_escape_string($conn, $_POST['sbname']);
	$dsbname = mysqli_real_escape_string($conn, $_POST['dsbname']);
	$date = mysqli_real_escape_string($conn, $_POST['date']);
	$time = mysqli_real_escape_string($conn, $_POST['time']);

	// Set the timezone to NZ
	date_default_timezone_set('Pacific/Auckland');

	// Get the generated booking date and time, i.e., the current date and time
	$currentDate = date('Y-m-d');
	$currentTime = date('H:i');

	// Convert date to dd/mm/yyyy format
	$dateObj = DateTime::createFromFormat('Y-m-d', $date);
	$formattedDate = $dateObj->format('d/m/Y');

	// Insert the booking into the database
	$insertQuery = "INSERT INTO bookings (bkref, cname, phone, unumber, snumber, stname, sbname, dsbname, date, time, status, generated_bkdate, generated_bktime)
                		VALUES ('$bkref', '$cname', '$phone', '$unumber', '$snumber', '$stname', '$sbname', '$dsbname', '$date', '$time', 'unassigned', '$currentDate', '$currentTime')";
	$insertResult = mysqli_query($conn, $insertQuery);

	if ($insertResult) {
    		// Output booking details upon successful insertion into database
     		$responseData = array(
        		'bkref' => $bkref,
        		'time' => $time,
        		'date' => $formattedDate
    		);
    		echo json_encode($responseData);
	} else {
    		echo "Error: " . mysqli_error($conn);
	}

	// Free result set
	mysqli_free_result($result);

	// Close connection
	mysqli_close($conn);
?>