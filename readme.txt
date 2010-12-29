=== Plugin Name ===
Contributors: GetSatisfaction
Donate link: http://getsatisfaction.com
Tags: getsatisfaction
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 1.1

GetSatisfaction plugin that will allow you to integrate with GetSatisfaction to create, manage, and display
Topics and Replies.

== Description ==


== Installation ==

Install Instructions
--------------------

Setup PHP Environment

	1. Install Pear HTTP_Request
		pear install HTTP_Request-1.4.4

	2. Install XML_Feed_Parser	
		pear install XML_Feed_Parser-1.0.3

Install Plugin
	1. Unzip content of get-satisfaction-for-wordpress.zip
	2. Copy to <wordpress>/wp-content/plugins
	3. Activate Plugin

Setup Theme

	1. Copy from the etsatisfaction plugin directory /theme directory
	
	topic-comments.php (replies template)
	topic.php (single post template for topcis)

	* you can customize these templates for your themes needs using
	  the function available in the getsatisfaction-templatetags.php
	  	  
Authorize Get Satisfaction API Access

	1. Register for Get Satisfaction account at http://getsatisfaction.com
	2. Under "Account Settings" goto "API Services", reigster for an API Key
	3. In Wordpress Main Menu, go to "Get Satisfaction Configuration Page and you'll see 
	   two paramters API KEY, API Secret enter those in and authorize
	   
	* more detailed instructions is available in the Get Satisfaction Configuration Page. 	   		  

3rd Party Library Requirements
------------------------------

3rd Party Libraries

[Install from PEAR]
* http://pear.php.net/package/HTTP_Request/download
* http://pear.php.net/package/XML_Feed_Parser/download

pear install HTTP_Request-1.4.4
pear install XML_Feed_Parser-1.0.3

[Included]
* http://code.google.com/p/hkit/
* http://teczno.com/HTTP_Request_Oauth.phps

== Frequently Asked Questions ==

= Where can I get a Get Satisfaction API Key? =

You must create a Get Satisfaction Account and genereate an API Key under the API Services in the
user dashboard.


== Screenshots ==


== Changelog ==
