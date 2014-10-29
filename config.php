<?php
$upload_dir = wp_upload_dir();

// Default value for Teaser Widget
define('TEASER_WIDGET_DEFAULT_TITLE', 'Featured Events');
define('TEASER_WIDGET_DEFAULT_NUM_DISPLAY', 5);
define('TEASER_WIDGET_DEFAULT_BG_COLOR', '#ffffff');
define('TEASER_WIDGET_DEFAULT_TITLE_COLOR', '#000000');
define('TEASER_WIDGET_DEFAULT_EVENT_TITLE_SIZE', 12);
define('TEASER_WIDGET_DEFAULT_EVENT_DATE_SIZE', 11);
define('TEASER_WIDGET_DEFAULT_LOGO_POSITION', 'b_right');
define('TEASER_WIDGET_DEFAULT_ROUNDED_CORNER_RADIUS', 5);

// Config for the xml process data

define('CALENDAR_UPLOAD_DIR', $upload_dir['basedir']. '/artsopolis-calendar' );
define('XML_FILE_PATH', CALENDAR_UPLOAD_DIR. '/'. ac_get_current_domain() .'/artsopolis-calendar.xml');
define('OVERRIDE_TIME_XML_FILE', 1);  // The time after creating the file for auto overriding xml feed file
define('TIMEOUT_REQUEST_GET_XML_CONTENT', 1000);

define('FRONT_END_PAGE_SIZE', 20);
define('DELIMITER_DATE', '-');

define('AC_PLUGIN_DEFAULT_LOGO_POSITION', 't_right');
