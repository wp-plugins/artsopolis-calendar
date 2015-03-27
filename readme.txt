=== Artsopolis Calendar ===
Contributors: nhanlt, vulh
Tags: calendar, artsopolis-calendar, artsopolis, apollo
Requires at least: 3.3
Tested up to: 4.0.1
Stable tag: 2.0
License: GPLv2 or later

Arsopolis Calendar will provide a basic list of discount ticket

== Description ==

Arsopolis Calendar will provide a basic list of discount ticket

== Installation ==

1. Install the Artsopolis Calendar plugin either via the WordPress.org plugin directory, or by uploading the files to your server. Use the "Plugins" tab located in the left-side navbar of the Dashboard. Select "Add New" from the "Plugins" tab and enter artsopolis calendar in the Search box. Then click on "Install now" link. After download is complete, click on "Activate Plugin".
2. After installing plugin, access Plugins -> Artsopolis Calendar to make your own configuration
3. Click "Save Changes." Once your changes are saved add the following string to a page on your site - including brackets and insert: [artsopolis-calendar-plugin]
4. Be default, after 1 hour, list events will be refreshed, you can change this option by [artsopolis-calendar-plugin hour=#]. Please replace "#" with approriate hours
5. Other requirements: *Write permission of artsopolis-calendar/xml directory is required. *cURL extension is required. *simplexml extension is required. *json extension is required. *openssl extension is required.

== Screenshots ==

1. Admin configuration
2. Frontend of plugin

== Changelog ==

= 1.0 =
* Provide list of discount offers
* Provide keyword, category, date and location filtering
* Detail event page can access internal plugin or external depend on backend configuration

= 1.1 =
* Fix layout

= 1.2 =
* Filter events by tag
* Optimize performance

= 1.3 =
* Update plugin's css to run compatible with most of themes

= 1.3.2 =
* Allow user to active "teaser widget" that list feature events in sidebar

= 1.3.3 =
* Optimize style "teaser widget"
* Allow upload logo and configure position for plugin and widget
* More configuration for "teaser widget"

= 1.3.4 =
* Fix css for "teaser widget"

= 1.3.5 =
* Allow configure rounded/squared corners title bar of "teaser widget"

= 1.4 =
* Fix bug subcat is empty
* Separate Current & Upcoming and Ongoing events tabs

= 2.0 =
* Allow multi feeds
* Allow multi teaser widgets
* Allow site admin to config which teaser widget will associate with specific feed
* Sort by "Start Date" option will be sorted by first date in bunch of "upcomming date" instead of real "Start Date"