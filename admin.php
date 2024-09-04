<?php
//File: admin.php
//Student: 19079062 scc1338
//This file responds to the search request and returns matching bookings of the inputted booking reference

	// Include database connection settings
	require_once("../../files/settings.php");

	// Establish connection to the database
	$conn = mysqli_connect($host, $user, $pswd, $dbnm);

	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// Check if assign_booking is set
	if (isset($_POST['assign_booking'])) {
		$bkref = mysqli_real_escape_string($conn, $_POST['assign_booking']);

		// If set, Update the status of the booking request in the database
		$updateQuery = "UPDATE bookings SET status = 'assigned' WHERE bkref = '$bkref'";
		if (mysqli_query($conn, $updateQuery)) {
			//Print confirmation message
			echo "Congratulations! Booking request $bkref has been assigned!";
		} else {
			echo "Error: ".mysqli_error($conn);
		}

		mysqli_close($conn);
		exit;
	}

    	// Get search string passed from client
	$bsearch = mysqli_real_escape_string($conn, $_POST['bsearch']);
	
	// Check if search string is not empty
	if (!empty($bsearch) && !preg_match("/^\s*$/", $bsearch)) {
		// Not empty search string
		// Check if the search string is in the right format
		if (!preg_match("/^BRN\d{5}$/", $bsearch)) {
			// Search string is in wrong format
			echo "The search string is in the wrong format! Must be in format 'BRN' followed by 5 digits!";
		} else {
			// Seach string is in correct format
			    // Retrieve data from the table for the given booking reference
			$getDataQuery = "SELECT bkref, cname, phone, sbname, dsbname, 
				CONCAT(DATE_FORMAT(date, '%d/%m/%Y'), ' ', TIME_FORMAT(time, '%H:%i'))
				AS pickup_datetime, status 
				FROM bookings WHERE bkref='$bsearch'";
			$result = mysqli_query($conn, $getDataQuery);

			// Check if any rows are returned
			if (mysqli_num_rows($result) > 0) {
				echo "<table>";
				echo "<tr><th>Booking Reference Number</th><th>Customer Name</th><th>Phone</th>
				<th>Pickup Suburb</th><th>Destination Suburb</th><th>Pickup Date and Time</th>
				<th>Status</th><th>Assign</th></tr>";

				// Fetch row returned from sql query
				$row = mysqli_fetch_row($result);

				// Display row matching booking reference
				echo "<tr><td>{$row[0] }</td>";
				echo "<td>{$row[1] }</td>";
				echo "<td>{$row[2] }</td>";
				echo "<td>{$row[3] }</td>";
				echo "<td>{$row[4] }</td>";
				echo "<td>{$row[5] }</td>";
				echo "<td>{$row[6] }</td>";
				if ($row[6] == 'unassigned') {
					echo "<td><button onclick=\"assignBooking('{$row[0]}')\">Assign</button></td></tr>";
				} else {
					echo "<td><button disabled>Assigned</button></td></tr>";
				}			

				echo "</table>";
			} else {
				echo "No booking found with that reference number.";
			}
		}
	} else {
		// Empty search string
		// Retrieve data from the table for bookings within next 2 hours THAT ARE UNASSIGNED
		$getDataQuery = "SELECT bkref, cname, phone, sbname, dsbname, 
			CONCAT(DATE_FORMAT(date, '%d/%m/%Y'), ' ', TIME_FORMAT(time, '%H:%i'))
			AS pickup_datetime, status  
			FROM bookings WHERE status = 'unassigned' AND
			CONCAT(date, ' ', time) BETWEEN NOW() and DATE_ADD(NOW(), INTERVAL 2 HOUR)";
		$result = mysqli_query($conn, $getDataQuery);

		// Check if any rows are returned
		if (mysqli_num_rows($result) > 0) {
			echo "<table>";
			echo "<tr><th>Booking Reference Number</th><th>Customer Name</th><th>Phone</th>
			<th>Pickup Suburb</th><th>Destination Suburb</th><th>Pickup Date and Time</th>
			<th>Status</th><th>Assign</th></tr>";

			// Fetch row returned from sql query
			$row = mysqli_fetch_row($result);

			// Display rows matching booking reference
			while ($row) {
				echo "<tr><td>{$row[0] }</td>";
				echo "<td>{$row[1] }</td>";
				echo "<td>{$row[2] }</td>";
				echo "<td>{$row[3] }</td>";
				echo "<td>{$row[4] }</td>";
				echo "<td>{$row[5] }</td>";
				echo "<td>{$row[6] }</td>";
				echo "<td><button onclick=\"assignBooking('{$row[0]}')\">Assign</button></td></tr>";
				$row = mysqli_fetch_row($result);
			}			

			echo "</table>";
		} else {
			echo "No unassigned bookings ready to be picked up!";
		}
	}
	

	// Free result set
	mysqli_free_result($result);

	// Close connection
	mysqli_close($conn);
?>