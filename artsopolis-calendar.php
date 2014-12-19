<?php

/**
 * @package Artsopolis Calendar plugin
 * @version 1.4.1
 */
/*
  Plugin Name: Artsopolis Calendar
  Plugin URI: http://wordpress.org/plugins/artsopolis-calendar/
  Description: Artsopolis Calendar
  Author: Artsopolis
  Author URI: www.artsopolis.com
  Version: 1.4.2
 */

/* Remove options when uninstall */
function ac_uninstall() {
    delete_option('artsopolis_calendar_options');
}

register_uninstall_hook(__FILE__,'ac_uninstall');

if (! class_exists("Artsopolis_Calendar")) {
    
    class Artsopolis_Calendar {

        private static $is_page_shortcode = false;

        public static function init() {
            register_activation_hook(__FILE__, array(__CLASS__, 'ac_active'));
            register_deactivation_hook(__FILE__, array(__CLASS__, 'ac_deactive'));
            add_action('init', array(__CLASS__, 'register_scripts'));
            add_action('wp_footer', array(__CLASS__, 'print_scripts'));
            
            add_action( 'widgets_init', array(__CLASS__, 'load_widgets') );
        }

        public static function ac_active() {
            
            /* Only set when the first time install plugin */
            if(get_option('artsopolis_calendar_options', false) === false) {
                update_option('artsopolis_calendar_options', array(
                    'title'                      => '',                     
                    'feed_url'                   => '',
                    'settings_display_color'     => '#000000',
                    'settings_display_order'     => 'START-DATE-ASC',
                    'filter_date'                => '1',
                    'filter_location'            => '1',
                    'filter_price'               => '1',
                    'feed_valid'                 => '0',
                    'has_changed'                => 0,
                    'content'                    => '',
                    'category'                   => '',
                    'category_xml_feed_url'      => '',
                    'details_link_to'			 => 1, // Default link to source, 0 link to plugin,
                    'all_cats'                   => '', // Save all categories to get the list tags in the list events,
                    'category_valid'             => '',
                    'calendar_slug'              => '',
                    'teaser_widget_logo_url'     => '',
                    'teaser_widget_logo_position'=> TEASER_WIDGET_DEFAULT_LOGO_POSITION,
                    'plugin_logo_url'            => '',
                    'plugin_logo_position'       => AC_PLUGIN_DEFAULT_LOGO_POSITION,
                    'display_search_bar'         => 1,   
                    'teaser_widget_logo_link_to' => '',   
                ));
            }
            
        }

        public function ac_deactive() {
            // Do nothing
        }
        
        public static function register_scripts() {
            wp_enqueue_script( 'jquery-ui-datepicker' ); 
            wp_register_style('jquery-ui.css', plugins_url('artsopolis-calendar/css/jquery-ui.css'));
            wp_register_style('artsopolis-calendar.css', plugins_url('artsopolis-calendar/css/artsopolis-calendar.css'));
            
            wp_register_script('simple-pagination-js', plugins_url('js/jquery.simplePagination.js', __FILE__), array('jquery'), '1.0', true);
			
			// For gmap
            if ( isset( $_REQUEST['event_id'] ) && $_REQUEST['event_id'] ) {
                wp_register_script('artsopolis-calendar-gmap-lib', 'https://maps.googleapis.com/maps/api/js?sensor=false');
                wp_register_script('artsopolis-calendar-gmap', plugins_url('/artsopolis-calendar/js/artsopolis-calendar-gmap.js'), array('artsopolis-calendar-gmap-lib'));
            }
			

            wp_register_script('artsopolis-calendar-js', plugins_url('artsopolis-calendar/js/artsopolis-calendar.js'));
            wp_localize_script('artsopolis-calendar-js', 'artsopolis_calendar_obj', array(
                'calendar_src' => plugins_url('artsopolis-calendar/img/date-button.gif'),
                'admin_url'    => admin_url('admin-ajax.php'),
            ));
			
        }

        public static function setPageShortcode($boolValue) {
            self::$is_page_shortcode = $boolValue;
        }
        
        public static function print_scripts() {

            if(!self::$is_page_shortcode) return;

            wp_print_styles('jquery-ui.css');
            wp_print_styles('artsopolis-calendar.css');
            wp_print_scripts('artsopolis-calendar-js');
            wp_print_scripts('simple-pagination-js');
			
            if ( isset( $_REQUEST['event_id'] ) && $_REQUEST['event_id'] ) {
                wp_print_scripts('artsopolis-calendar-gmap-lib');
                wp_print_scripts('artsopolis-calendar-gmap'); 
            }
			
        }
        
        public static function load_widgets () {
            
            // register the events widget
            register_widget( 'artsopolis_calendar_teaser_widget' );
        }
    }
}

Artsopolis_Calendar::init();


if (! class_exists('Artsopolis_Calendar_API')) {
    class Artsopolis_Calendar_API {
        public static   $feed_url,
                        $categories_url = '';
        
        /**
         * Get content from url
         */
        public static function get_request_content($url) {
            $request = new WP_Http;
            
            $content = $request->request($url, array(
                'timeout' => TIMEOUT_REQUEST_GET_XML_CONTENT,
            ));
            
            if (is_wp_error($content)) {
                return false;
            }
            
            return $content['body'];            
        }
        
        public static function get_cdata_xml($cdata) {
            $event_date_times = $cdata->eventDatesTimes->datetime;
            $event_times = array();
            
            if ($event_date_times) {
                foreach ($event_date_times as $item) {
                    $event_times[] = (object) array(
                        'date'  => (string) $item->date,
                        'time'  => (string) $item->time
                    ); 
                }
            }
            
            return (object) array(
                'orgImage'          => (string) $cdata->orgImage,
                'eventImage'        => (string) $cdata->eventImage,
                'eventName'         => (string) $cdata->eventName,
                'eventDateBegin'    => (string) $cdata->eventDateBegin,
                'eventDateEnd'      => (string) $cdata->eventDateEnd,
                'eventDatesTimes'   => (string) $cdata->eventDatesTimes,
                'orgName'           => (string) $cdata->orgName,
                'eventUrl'          => (string) $cdata->eventUrl,
                'venueID'           => (string) $cdata->venueID,
                'venueName'         => (string) $cdata->venueName,
                'venueAddress1'     => (string) $cdata->venueAddress1,
                'venueAddress2'     => (string) $cdata->venueAddress2,
                'venueCity'         => (string) $cdata->venueCity,
                'venueState'        => (string) $cdata->venueState,
                'venueZip'          => (string) $cdata->venueZip,
                'venueLatitude'     => (string) $cdata->venueLatitude,
                'venueLongitude'    => (string) $cdata->venueLongitude,
                'eventPhone1'       => (string) $cdata->eventPhone1,
                'eventPhone2'       => (string) $cdata->eventPhone2,
                'eventEmail'        => (string) $cdata->eventEmail,
                'eventTicketUrl'    => (string) $cdata->eventTicketUrl,
                'eventStartTime'    => (string) $cdata->eventStartTime,
                'eventType'         => (string) $cdata->eventType,
                'tags'              => (string) $cdata->tags,
                'eventDescription'  => (string) $cdata->eventDescription,
                'link'              => (string) $cdata->link,
                'eventID'           => (string) $cdata->eventID,
                'event_dates_times' => $event_times,
                'eventTicketInfo'   => (string) $cdata->eventTicketInfo,
                'discountUrl'       => (string) $cdata->discountUrl,
                'categories'        => (string) $cdata->categories,
            );
        }
        
        private static function _create_dir( $path ) {
            
            if ( ! is_dir( $path ) ) {
                mkdir( $path );
            }
            
            if ( ! is_writable( $path ) ) {
                @chmod( $path, 0777 );
            }
        }
        
        /**
         * Save xml file
         * @author: vulh
         * return void
         */
        public static function save_xml_data() {
            $data = self::get_request_content(self::$feed_url);

            if ($data) {
                
                self::_create_dir( CALENDAR_UPLOAD_DIR );
                self::_create_dir( CALENDAR_UPLOAD_DIR. '/'. ac_get_current_domain() );
                
                // Process xml file
                if (! is_writable(XML_FILE_PATH)) {
                    @chmod(XML_FILE_PATH, 0777);
                }
                
                $result = @file_put_contents(XML_FILE_PATH, $data);
                if ($result  === false) {
                    exit('Please set write permission for '.CALENDAR_UPLOAD_DIR.'/'.ac_get_current_domain().' folder');
                }
            } else {
                exit('Please re-check plugin configuration <a href="/wp-admin/admin.php?page=admin-artsopolis-calendar">Click here</a>');
            }
        }
        
        /**
         * Check can override the xml file
         * @author: vulh
         * return boolean
         */
        public static function check_can_override_xml_file($hour) {
            $ac_options = get_option('artsopolis_calendar_options');
            $has_change_api = $ac_options['has_changed'];
           
            $filename = XML_FILE_PATH;
            $time_create = @filemtime($filename);
            $can_modify_time = $time_create + $hour * 3600;
            
            // Update option
            if ($ac_options['has_changed']) {
                $ac_options['has_changed'] = 0;
                update_option('artsopolis_calendar_options', $ac_options);
            }
            
            return ! file_exists($filename) || time() > $can_modify_time || $has_change_api || ! $ac_options['feed_url'];
        }
        
        public static function get_categories() {
            $data = @simplexml_load_string(self::get_request_content(self::$categories_url));
            $result = array();
                
            if ( ! is_object( $data ) ) {
                return array();
            }
            
            $cats = $data->cat_id;
            $sub_cats = $data->subcat_ids;
            
            if (! count($cats) || ! count($sub_cats)) {
                return array();
            }
            
            foreach ($cats as $cat) {
                $cat = explode('_', (string) $cat);
                $result[$cat[0]]['name'] = $cat[2];
				$result[$cat[0]]['key']  = $cat[0]. '_'. $cat[1];
                $result[$cat[0]]['subcats'] = array();
            }
           
            foreach ($sub_cats as $sub_cat) {
                
                foreach ($sub_cat->subcat_id as $sub_cat_id) {
                    $sub_cat_id = explode('_', $sub_cat_id);
                    $result[$sub_cat_id[0]]['subcats'][] = $sub_cat_id[2].'[+]'.$sub_cat_id[0].'_'.$sub_cat_id[1];
                }
                $result[$sub_cat_id[0]]['subcats'] = self::sort_sub_category($result[$sub_cat_id[0]]['subcats']);
            }
			
            $result = self::sort_parent_category($result);
         
            return $result;
        }
        
        public static function sort_parent_category(&$result) {
            usort($result, 'ac_admin_parent_sort_by_alpha');
            return $result;
        }
        
        public static function sort_sub_category(&$result) {
            usort($result, 'ac_admin_sub_sort_by_alpha');
            $after_sort = array();
            foreach ($result as $item) {
                $arr = explode('[+]', $item);
                $after_sort[$arr[1]] = $arr[0];
            }
            return $after_sort;
        }
    }
}
require_once(dirname(__FILE__). '/function.php');
require(dirname(__FILE__). '/config.php');
require_once(dirname(__FILE__). '/widgets/teaser/teaser.php');

require_once(dirname(__FILE__). '/frontend/shortcode.php');

if (is_admin()) {
    require_once(dirname(__FILE__). '/admin/admin.php');
    
    /* Active settings link */
    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'ac_add_settings_link', 10);
    function ac_add_settings_link($links) {
        $settings_link = '<a href="admin.php?page=admin-artsopolis-calendar">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}