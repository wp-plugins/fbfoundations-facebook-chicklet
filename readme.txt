==== FBFoundations Facebook Chicklet ====
Contributors: Jesse Stay
Tags: Facebook, chicklet, fan page, fan, number of fans, button, feedburner
Requires at least: 2.8
Tested up to: 2.8.4
	
Gives you a simple "Number of Fans" chicklet you can place anywhere on your
blog. Clicking on the chicklet takes users to your Facebook Page. Requires the
FBFoundations plugin located here: http://staynalive.com/fbfoundations

== Description ==

FBFoundations Facebook Chicklet is a WordPress Plugin which lets you add a "Number of Fans" Chicklet (button) for your Facebook Page (also known as a "Fan Page") anywhere on your Wordpress blog. The Chicklet uses Facebook Connect to determine the number of fans for your Facebook Page and displays them for your readers in a little button you can add anywhere on your blog.  This requires the FBFoundations plugin to be activated, which you can download and install here: http://staynalive.com/fbfoundations

###Usage###

To get started, download the Facebook Chicklet plugin, and extract it into
your Wordpress plugins folder.

#### Setup

Before it will work, you need to at least specify the ID for your Facebook
Page.  Simply go to the "Facebook Chicklet" settings under your "Settings"
section in your Wordpress admin and you'll see the box to enter your Facebook
Page ID (this defaults to the author's own Facebook Page so you'll want to
change it).

To find the Page ID for your Page, just click on the main image in the
upper-left of the Page, and the ID will be next to the "id=" in the URL.  Just
copy and paste that into the text box and hit save.

You can also customize the colors and look and feel of the Chicklet in the
other boxes in your settings.  If you need more flexibility you may want to
consider modifying the CSS of your theme's main CSS file.

#### Implementation

To implement your Chicklet, just place:

<?php if (function_exists('fbchicklet_button')) echo fbchicklet_button(); ?>

anywhere in your blog's theme files.  That's it!

More information available at the [Plugins home page][1].

 [1]: http://staynalive.com/fbchicklet
	

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Screenshots ==
1. Settings page

2. Example Chicklet implementation

== Changelog ==

###v0.1 (2009-12-14)

*   Initial Release
