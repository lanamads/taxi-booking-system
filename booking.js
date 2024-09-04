// File: booking.js
// Student: 19079062 scc1338
// This file contains function createBookingRequest to fetch data from the server to create a booking request 
// and function checkPickupDateTimeValidity to validate the date and time inputs from the form.
// Functions updateDateTimeInputs and updateTimeInput set the date and time inputs to the current time

// This event listener checks if the html file has been loaded and sets the date and time input fields to the current date and time
document.addEventListener('DOMContentLoaded', function() {
	// Set default values for pickup date and time inputs to the current date and time
	updateDateTimeInputs();

	// Update the pickup time input every second if the user hasn't changed it
	setInterval(updateTimeInput, 1000);
});

// This function is called when the html file is loaded and sets the default date and calls the updateTimeInput function
function updateDateTimeInputs() {
	// Set default value for date input
	var currentDate = new Date(); // Get current date
	document.getElementById('pickupDate').defaultValue = currentDate.toLocaleDateString('en-CA'); //Set date to current date

	// Set default value for time input
	updateTimeInput();
}

// This function updates the time input to the current time
function updateTimeInput() {
	// Get the current time
	var currentTime = new Date().toTimeString().split(' ')[0];
	var currentTimeParts = currentTime.split(':');
	var currentTimeWithoutSeconds = currentTimeParts[0] + ':' + currentTimeParts[1];
	
	// Get the pickup time input element
	var pickupTimeInput = document.getElementById('pickupTime');

	// Update the pickup time input only if the user hasn't changed it or the page is first loaded
	if (pickupTimeInput.value === pickupTimeInput.defaultValue || pickupTimeInput.value === "") {
		pickupTimeInput.value = currentTimeWithoutSeconds;
		pickupTimeInput.defaultValue = pickupTimeInput.value;
	}
}

// This function validates that the inputted date and time is not earlier than the current date and time
function checkPickupDateTimeValidity() {
	// Retrieve pickup date and time inputs from form
	var pickupDateInput = document.getElementById('pickupDate');
	var pickupTimeInput = document.getElementById('pickupTime');
	
	// Get pickup date and time values
	var pickupDateValue = pickupDateInput.value;
	var pickupTimeValue = pickupTimeInput.value;

	// Get the current date and time
	var currentDate = new Date().toLocaleDateString('en-CA');
	var currentTime = new Date().toTimeString().split(' ')[0]; //Get current time
	var currentTimeParts = currentTime.split(':');
	var currentTimeWithoutSeconds = currentTimeParts[0] + ':' + currentTimeParts[1];

	// Clear current validation message
	document.getElementById('pickupDate').setCustomValidity('');
	document.getElementById('pickupTime').setCustomValidity('');

	// Compare pickup time and date values to current date and time
	if (pickupDateValue < currentDate) {
		document.getElementById('pickupDate').setCustomValidity('Pickup date cannot be before today\'s date.');
	} else if (pickupDateValue === currentDate) {
		if (pickupTimeValue < currentTimeWithoutSeconds) {
			document.getElementById('pickupTime').setCustomValidity('Pickup time cannot be before current time');
		}
	} else {
		document.getElementById('pickupDate').setCustomValidity('');
		document.getElementById('pickupTime').setCustomValidity('');
	}

	document.getElementById('pickupDate').reportValidity();
	document.getElementById('pickupTime').reportValidity();
}

// This function is called when the user presses the create booking button on the booking.html file
// It returns a confirmation message upon successful creation of the booking request
function createBookingRequest(dataSource, divID)
{
	var form = document.getElementById('bookingForm');  // Get the specific form element by ID
	if (!form) {
		// If form not found print error
		console.error('Form not found');
		return;
	}

	// Validate form inputs
	if(!form.checkValidity()) {
		form.reportValidity();
		return;
	}

	var place = document.getElementById(divID);
	var url = dataSource;
	var data = new FormData(form);

	fetch(url, {
		method: 'POST',
		body: data
	})
	.then(response => response.text())
	.then(data => {
		// Handle the JSON data
		var bookingData = JSON.parse(data);
		// Display the confirmation message
		place.innerHTML = "<p>Thank you for your booking!</p>" +
		"<p>Booking reference number: <span>" + bookingData.bkref +"</span></p>" +
		"<p>Pickup time: <span>" + bookingData.time + "</span></p>" +
		"<p>Pickup date: <span>" + bookingData.date+"</span></p>";
	})
	.catch(error => {
		console.error('Error:', error);
	});
}