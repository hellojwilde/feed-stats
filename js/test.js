/*
    Copyright (c) 2008 - 2009 Jonathan Wilde

    This file is part of Feed Stats for WordPress.

    Feed Stats for WordPress is free software: you can redistribute 
    it and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 2 of the License, 
    or (at your option) any later version.

    Feed Stats for WordPress is distributed in the hope that it will 
    be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Feed Stats for WordPress.  If not, see 
    <http://www.gnu.org/licenses/>.
*/

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
        var testURL = tester + "&feed=" + 
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

        var result = XHR.responseText.match(/\[result\].*\[result\]/g);
        result = result[0].replace(/\[result\]/gi, '');     

        // Check the response text to see whether the feed is valid
        if (result.substring(0, 5) == '[-5] ') {
            // Display the check mark icon and the response text
            good.style.display = "inline";
            good.innerHTML = good.title + result.substring(5);
        } else {
            // Display the exclamation icon and the response text
            bad.style.display = "inline";
            bad.innerHTML = bad.title + result.substring(5) + 
                ' (<a href="./options-general.php?page=feed-stats-options&mode=help" target="_new">' + help + '</a>)';
        }
    }
}
