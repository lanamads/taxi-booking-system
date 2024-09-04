//File admin.js
//Student: 19079062 scc1338
//This file contains functions to fetch data from server based on users button clicks
//It contains function searchRequest to search the database for a booking reference
// and function assignBooking to assign a taxi to a booking reference

//The searchRequest function is called when the user presses the search button on the admin.html file
//It returns a table of taxi bookings from the user's form input
function searchRequest(dataSource, divID, bSearch)
{
	document.getElementById('message').innerHTML = "";
	var place = document.getElementById(divID);
	var url = dataSource;
	var data = new FormData();
	data.append('bsearch', bSearch);

	const requestPromise = fetch(url, {
		method: 'POST',
		body: data
	});
	requestPromise.then(
		function (response){
			response.text().then(function(text) {
				//Print the taxi booking search request results to the html
				place.innerHTML = text;
			});

		}
	);
}

//The assignBooking function is called when the user presses the assign button on the resulting table of the searchRequest
//It returns a confirmation message upon successful assignment of the taxi booking
function assignBooking(bkref) {
	var data = new FormData();
	data.append('assign_booking', bkref);

	fetch('admin.php', {
		method: 'POST',
		body: data
	})
	.then(response => response.text())
	.then(text => {
		//Print confirmation message to the html
		var messageDiv = document.getElementById('message');
		messageDiv.innerHTML = text;

		//Update the table row to reflect the change in assignment status
		var rows = document.querySelectorAll('table tr');
		rows.forEach(row => {
			var cells = row.querySelectorAll('td');
			if (cells.length > 0 && cells[0].textContent === bkref) {
				// Update the status column to reflect database
				cells[6].textContent = 'assigned';

				// Update the assign button
				var button = cells[7].querySelector('button');
				button.textContent = 'Assigned';
				button.disabled = true;
			}
		});
	})
	.catch(error => console.error('Error:', error));
}