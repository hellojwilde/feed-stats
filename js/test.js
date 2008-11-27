// Grab all of the different testing icons so that we can toggle their 
// visibility to give the user feedback
var tester = document.getElementById('feed-stats-tester');
var bad = document.getElementById('feed-stats-result-bad');
var good = document.getElementById('feed-stats-result-good');
var waiting = document.getElementById('feed-stats-waiting');

// We're going to display the tester button so that the user can click it
tester.style.display = "inline";

function testURL(tester) {
	// Let's try to grab a copy of XHR
	try { 
		// If the browser is standards-compliant (IE 7+, Opera, Mozilla, 
		// Safari, etc.)
		var XHR = new XMLHttpRequest() 
	} catch (e) { 
		// If the browser is IE 6-
		var XHR = new ActiveXObject("Msxml2.XMLHTTP");
	}
	
	// Let's make sure that we have an XHR object before we go on
	if (XHR) {
		// We'll set up an event handler so we can notify the user of 
		// progress
		XHR.onreadystatechange = function () {
			testChange(XHR.readyState, XHR);
		}
		
		// Figure out what the url to the test script will be
		var testURL = tester + "?feed=" + 
			document.getElementById("feed-stats-feed").value;
		
		// Send the request
		XHR.open("GET", testURL, true);
		XHR.send(null);
	}
}
			
function testChange(state, XHR) {
	if (state == 1) {
		// If the request has been sent but we don't have a response 
		// from the server yet, we'll display the throbber
		tester.style.display = "none";
		bad.style.display = "none";
		good.style.display = "none";
		
		waiting.style.display = "inline";
	} else if (state == 4) {
		// We'll hide the throbber and display the "test" button again
		waiting.style.display = "none";
		tester.style.display = "inline";
		
		// Check the response text to see whether the feed is valid
		if (XHR.responseText == 'The feed is valid.') {
			// Display the check mark icon and the response text
			good.style.display = "inline";
			good.innerHTML = good.title + XHR.responseText;
		} else {
			// Display the exclamation icon and the response text
			bad.style.display = "inline";
			bad.innerHTML = bad.title + XHR.responseText + 
				'. (<a href="./options-general.php?page=feed-stats&help=true" target="_new">' + help + '</a>)';
		}
	}
}
