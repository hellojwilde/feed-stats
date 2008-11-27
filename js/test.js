var tester = document.getElementById('feed-stats-tester');
var bad = document.getElementById('feed-stats-result-bad');
var good = document.getElementById('feed-stats-result-good');
var waiting = document.getElementById('feed-stats-waiting');
		
tester.style.display = "inline";
			
function testURL(tester, abs, inc) {
	try { 
		var XHR = new XMLHttpRequest() 
	} catch (e) { 
		var XHR = new ActiveXObject("Msxml2.XMLHTTP");
	}	if (XHR) {
		XHR.onreadystatechange = function () {
			testChange(XHR.readyState, XHR);
		}
				
		var testURL = tester + 
				  "?feed=" + document.getElementById("feed-stats-feed").value + 
				  "&abs=" + abs + 
				  "&inc=" + inc;
					
		XHR.open("GET", testURL, true);
		XHR.send(null);
	}
}
			
function testChange(state, XHR) {
	if (state == 1) {
		tester.style.display = "none";
		bad.style.display = "none";
		good.style.display = "none";
		
		waiting.style.display = "inline";
	} else if (state == 4) {
		waiting.style.display = "none";
		tester.style.display = "inline";
		
		if (XHR.responseText == 'The feed is valid.') {
			good.style.display = "inline";
			good.innerHTML = good.title + XHR.responseText;
		} else {
			bad.style.display = "inline";
			bad.innerHTML = bad.title + XHR.responseText + '. (<a href="./options-general.php?page=feed-stats&help=true">' + help + '</a>)';
		}
	}
}
			
function displayTroubleshooting() {
	document.getElementById('feed-stats-ts-box').style.display = "block";
}
			
function hideTroubleshooting() {
	document.getElementById('feed-stats-ts-box').style.display = "none";
}
