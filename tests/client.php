<?php
// Make the library happy by defining WPINC
define('WPINC', null);

// Load SimpleTest and Troy Wolf's class_http
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../include/class_http.php');
require_once(dirname(__FILE__) . '/../lang.php');
require_once(dirname(__FILE__) . '/../client.php');

// Create a fake version of the i18n __() function
function __($input) {
    return $input;
}

// Create a mock object out of class_http
Mock::generate('http');

class FetchRemoteXMLCase extends UnitTestCase {
    function testResponseSuccess () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        
        // Run the function
        $response = fetch_remote_xml('http://example.com/', $fetcher);
        
        // Assert what kind of response we want
        $this->assertReference($fetcher, $response);
    }
    
    function testResponseFailure () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnValue('fetch', false);
        
        // Run the function
        $response = fetch_remote_xml('example', $fetcher);
        
        // Assert what kind of response we want
        $this->assertFalse($response);
    }
}

class FetchFeedBurnerDataCase extends UnitTestCase {
    function testFeedNotFound () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        
        // Set the result of the Mock http object
        $fetcher->body = '<?xml version="1.0" encoding="utf-8" ?>
<rsp stat="fail">
    <err code="1" msg="Feed Not Found" />
</rsp>';
        $fetcher->status = 200;
        
        // Set what we're expecting from the function
        $fetcher->expect('fetch', array("https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=feed-not-found&"));
        
        // Run the function
        $response = fs_fetch_feedburner_data(
            "http://feeds.feedburner.com/feed-not-found",
            "GetFeedData", "", $fetcher);
        
        // Assert what kind of response we want
        $this->assertFalse($response['success']);
        $this->assertEqual(0, $response['error']['code']);
    }
    
    function testProtected () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        
        // Set the result of the Mock http object
        $fetcher->body = '<?xml version="1.0" encoding="utf-8" ?>
<rsp stat="fail">
    <err code="2" msg="This feed does not permit Awareness API access" />
</rsp>';
        $fetcher->status = 200;
        
        // Set what we're expecting from the function
        $fetcher->expect('fetch', array("https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=protected-feed&"));
        
        // Run the function
        $response = fs_fetch_feedburner_data(
            "http://feeds.feedburner.com/protected-feed",
            "GetFeedData", "", $fetcher);

        // Assert what kind of response we want
        $this->assertFalse($response['success']);
        $this->assertEqual(1, $response['error']['code']);
    }
    
    function test401 () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        $fetcher->status = 401;
        
        // Set what we're expecting from the function
        $fetcher->expect('fetch', array("https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=protected-feed-401&"));
        
        // Run the function
        $response = fs_fetch_feedburner_data(
            "http://feeds.feedburner.com/protected-feed-401",
            "GetFeedData", "", $fetcher);
                    
        // Assert what kind of response we want
        $this->assertFalse($response['success']);
        $this->assertEqual(1, $response['error']['code']);
    }
    
    function test500 () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        $fetcher->status = 500;
        
        // Set what we're expecting from the function
        $fetcher->expect('fetch', array("https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=fail-500&"));
        
        // Run the function
        $response = fs_fetch_feedburner_data(
            "http://feeds.feedburner.com/fail-500",
            "GetFeedData", "", $fetcher);
                    
        // Assert what kind of response we want
        $this->assertFalse($response['success']);
        $this->assertEqual(-3, $response['error']['code']);
    }
    
    function testFeedBurnerFail () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        $fetcher->body = null;
        
        // Set what we're expecting from the function
        $fetcher->expect('fetch', array("https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=fail&"));
        
        // Run the function
        $response = fs_fetch_feedburner_data(
            "http://feeds.feedburner.com/fail",
            "GetFeedData", "", $fetcher);
                    
        // Assert what kind of response we want
        $this->assertFalse($response['success']);
        $this->assertEqual(-2, $response['error']['code']);
    }
    
    function testFeedBurnerSuccess () {
        // Create a Mock http object
        $fetcher = &new Mockhttp();
        $fetcher->setReturnReference('fetch', $fetcher);
        
        // Set the result of the Mock http object
        $fetcher->body = '<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <!--This information is part of the FeedBurner Awareness API. If you want to hide this information, you may do so via your FeedBurner Account.-->
  <feed id="jr03po9jr2s063gei3v9obmcl0" uri="Speedbreeze">
    <entry date="2009-06-20" circulation="19" hits="32" reach="1" />
  </feed>
</rsp>';
        $fetcher->status = 200;
        
        // Set what we're expecting from the function
        $fetcher->expect('fetch', array("https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=protected-feed&"));
        
        // Run the function
        $response = fs_fetch_feedburner_data(
            "http://feeds.feedburner.com/protected-feed",
            "GetFeedData", "", $fetcher);

        // Assert what kind of response we want
        $this->assertTrue($response['success']);
    }
}