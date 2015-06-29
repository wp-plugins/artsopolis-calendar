=== Artsopolis Calendar ===

Contributors: nhanlt, vulh, jef

Tags: calendar, artsopolis-calendar, artsopolis, apollo

Requires at least: 3.3

Tested up to: 4.2.2

Stable tag: 2.0.3

License: GPLv2 or later



Artsopolis Calendar provides a searchable calendar of events for Artsopolis Network member content syndication partners.



== Description ==


Artsopolis Calendar provides a searchable calendar of events for Artsopolis Network member content syndication partners.


This plugin requires a valid API feed URL as issued by an approved Artsopolis Network member.


== Installation ==



1. Install the Artsopolis plugin either via the WordPress.org plugin directory, or by uploading the files to your server. Use the "Plugins" tab located in the left-side navbar of the Dashboard. Select "Add New" from the "Plugins" tab and enter "Artsopolis" in the Search box. Then click on "Install now" link. After download is complete, click on "Activate Plugin".

2. After installing the plugin contact your Artsopolis Network member to obtain a valid API feed URL.

3. Select "Plugins" > "Artsopolis Calendar" from the left column. On the calendar configuration form enter the event and category feed URLs provided to you by the Artsopolis Network member. Leave the Title and Body Text areas blank on this page.

4. In the "Artsopolis Calendar slug" field enter the page slug where you will be displaying the calendar. For example: "/calendar/" or "/events/"

5. Under "Display Settings" you can select the sort order for the event listings and a search bar background color.

6. In the two logo upload fields you can upload a 'powered by' or other type of sponsor logo along with a link. You can position where the logo displays on the main calendar page, as well as the 'teaser' widget.

7. Click "Save Changes." Once your changes are saved add the following snippet to the calendar page you created: [artsopolis-calendar-plugin fid=#]. Replace "#" with the appropriate ID number from the plugin you created and make sure to include the brackets.

8. This plugin allows you to add more than one calendar to your website. If you would like to add more than one calendar you will need to set up a separate page and add the same code snippet: [artsopolis-calendar-plugin fid=#]. Replace "#" with the appropriate ID number for any subsequent calendars you create. For example, your first calendar may be [artsopolis-calendar-plugin fid=0]; and then your second calendar may be [artsopolis-calendar-plugin fid=1]. Each one can use a different selection of filters (i.e. one for just Music events and another for just Family-Friendly events).

9. Other system requirements: *Write permission of goldstar/xml directory is required. *cURL extension is required. *simplexml extension is required. *json extension is required. *openssl extension is required.

10. You can also add a "teaser" widget to promote 1-15 featured events on the right column of your website. To add the featured event teaser widget, on the main calendar plugin admin configuration form enter the slug of the page you are adding the main Artsopolis plugin to. IMPORTANT: If the slug you enter for the widget does not match the slug of the page you have added the main Artsopolis plugin to the widget will NOT display. For more information about slugs, you may wish to visit this page: http://codex.wordpress.org/Glossary#Slug

9. From the main Artsopolis plugin Settings admin form select the "Featured Events" tab to select the specific events you would like to feature in the widget.  This tab is located at the top of form next to the "Configuration" tab just above the "Add New Post" field.

10. Next, select Appearance > Widget from the left column and then drag the "Artsopolis Calendar Teaser" widget to main sidebar position you prefer. Once positioned click to open the widget's display settings. You should enter a maximum number of events to be featured (between 1 and 15). You may also select various font size and color attributes to customize the widget's appearance.

11. Once you have completed the widget's display settings click "Save". Please note that the widget will only display those events that have been manually selected and will not auto fill events. So, once all selected featured events have expired, if no other events have been selected, the widget will automatically be suppressed from view.



== Screenshots ==

1. Admin configuration

2. Frontend of plugin



== Changelog ==


= 1.0 =
* Provide list of events
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
= 2.0.1 =
* Bug fixes
= 2.0.2 =
* Bug fix on line 280 of js/artsopolis-calendar-admin.js file
= 2.0.3 =
* Bug fix for incorrectly parsed html entities* Added .jpeg extension to allowed image types