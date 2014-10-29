<?php 
ob_start();
/**
 * This file will be generator the shortcode
 * It will be extended Artsopolis_Calendar_API 
 * in the artsopolis-calendar.php in the root of this plugin
 */

if (!class_exists('Artsopolis_Calendar_Shortcode')) {
    class Artsopolis_Calendar_Shortcode extends Artsopolis_Calendar_API {
        public static  $arr_filters = array(
            'START-DATE-ASC' => 'Start Date',
            'END-DATE-ASC'   => 'End Date',
            'ALPHABETA'      => 'Alphabetical (by event title)',
        );
        
        private static $_total_event = 0;
        
        public static function init() {
            // handle the shortcode to display in the frontend
            add_shortcode('artsopolis-calendar-plugin', array(__CLASS__, 'handle_shortcode'));
            
            // Register an action for ajax
            add_action( 'wp_ajax_nopriv_ac_get_feed', array(__CLASS__,'ac_get_feed' ));
            add_action('wp_ajax_ac_get_feed', array(__CLASS__, 'ac_get_feed'));
            
            if (isset($_GET['event_id']) && $_GET['event_id']) {
                add_action('wp_head', array(__CLASS__, 'add_meta_tags_fb'));
            }
        }

        public static function handle_shortcode($atts) {
            // Set the current url of the page or post, using for the ajax request when we handle the link for event
			if (!session_id()) {
			    session_start();
			}
          
            /* Only show javascripts on page have shortcode */
            Artsopolis_Calendar::setPageShortcode(true);
            
			// Only get the base url in the parent page
			if (! isset($_GET['event_id']) && ! isset($_GET['category'])) {
				$_SESSION['artsopolis_calendar_current_url'] = get_site_url(). $_SERVER["REQUEST_URI"];	
			}
            
            // Get options from the settings
            $ac_options = get_option('artsopolis_calendar_options');
            extract($ac_options, EXTR_PREFIX_ALL, 'ac');
            
            // Save xml data file from the api
            self::$feed_url = $ac_feed_url;
            
            if(! isset($_GET['event_id'])) {
                extract(shortcode_atts(array(
                    'hour' => isset($atts['hour']) && $atts['hour'] ? $atts['hour'] : OVERRIDE_TIME_XML_FILE 
                ), $atts));
                
                if (self::check_can_override_xml_file($hour)) {
                    Artsopolis_Calendar_API::save_xml_data();
                }
            }
         
            $xml = @simplexml_load_file(XML_FILE_PATH);
            
            // Get list location
            $locations_xml = array();
            if ($xml == false || ! $ac_options['feed_valid']) {
                echo 'The feed url is invalid. Please try to check it again';
                exit;
            }
            
            $locations_xml = $xml->xpath('event/venueCity');
            
            $_locations = array();
            if (! empty($locations_xml)) {
                foreach ($locations_xml as $location) {
                    if ($l = (string) $location) {
                        $_locations[rtrim($l)] = $l;
                    }
                }
            }
            
            $locations = array_values($_locations);
            asort($locations);
            
            // Get the category array for filter
            $category_data = self::_process_category_opt($ac_category);
            
			if (isset($_GET['event_id']) && $_GET['event_id']) {
                $event_id = $_GET['event_id'];
                $event = $xml->xpath("event[eventID=$event_id]");
				$html_events = self::get_detail_event($event);
			} else {
				
                // Filter by category when click on the tags
                if (isset($_GET['category']) && $_GET['category']) {
                    $categories = $_GET['category'];
                }
               
				// Get the list events and some value for the template
	            $events = self::get_list_events_data(array(
                    'page'      => 1, 
                    'category'  => $category_data['categories'],
                    'first_tab'   => true, // Filter all events has date end less than 2037-01-01 for second tab
                ));
	            
	            $total_event = self::$feed_url ? count($events) : 0;
	           
	            $page_size = FRONT_END_PAGE_SIZE;
                
                if (!session_id()) {
                    session_start();
                }
               
	            $html_events = self::get_html_list_events($events, array('page' => 1), $category_data['keys']);
			}
			
            // Render html and return
            ob_start();
            include dirname(__FILE__). '/frontend-template.php';
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
			
		}
		
        public static function add_meta_tags_fb() {
            $event_id = $_GET['event_id'];
            $xml = @simplexml_load_file(XML_FILE_PATH);
			
			if (! $xml) {
				return false;
			}
			
            $event = $xml->xpath("event[eventID=$event_id]");
			
			if (! empty($event)) {
				$event = self::get_cdata_xml($event[0]);
			} else {
                return false;
            }
            
            $desc = preg_replace("/<.*?>/", "", $event->eventDescription);
            echo '<meta property="og:title" content="'.$event->eventName.'"/>'
                . '<meta property="og:url" content="'.get_site_url(). $_SERVER["REQUEST_URI"].'"/>'
                . '<meta property="og:image" content="'.self::get_event_url($event->eventImage).'"/>'
                . '<meta property="og:description" content="'.substr($desc, 0, 200).'"/>';
        }
        
        public static function _process_category_opt($ac_category) {
            
            if (empty($ac_category)) {
                return array();
            }
            
            $categories = array();
            $cat_keys = array ();
            foreach ($ac_category as $key => $cats) {
                
                $categories[] = $key. '[+]'. ( isset( $cats['name'] ) ? $cats['name'] : '' );
                $cat_keys[] = $key;
                if (isset($cats['subs']) && $cats['subs']) {
                    foreach ($cats['subs'] as $key => $val) {
                        $categories[] = $key. '[+]'. $val;
                        $cat_keys[] = $key;
                    }
                }
            }
            return array('categories' => $categories, 'keys' => $cat_keys);
        }
        
		/**
		 * 	Get the detail event
		 * 	@param $event_id int 
		 * 	@return html
		 *  @author vulh	
		 * 	
		 * */
		public static function get_detail_event($event) {
			$ac_options = get_option('artsopolis_calendar_options');
			
            if (! $ac_options['category']) {
                return 'Please select at least a category to display events <a href="/wp-admin/admin.php?page=admin-artsopolis-calendar">Click here</a>';
            }
			
            $event = ! empty($event) ? self::get_cdata_xml($event[0]) : '';
            
			ob_start();
            include dirname(__FILE__) . '/detail-event-template.php';
            $html = ob_get_contents();
            ob_end_clean();
			
			return $html;
		}
		
        public static function get_list_events_data($arr_filter) {
            
            $page_size = FRONT_END_PAGE_SIZE;
            
            if ( ! file_exists(XML_FILE_PATH) || ! file_get_contents(XML_FILE_PATH) ) {
                return array();
            }
            
            $xml = simplexml_load_file(XML_FILE_PATH);
            
//            if ( isset( $arr_filter['first_tab'] ) && !$arr_filter['first_tab'] ) {
//                $arr_filter['date_end_ongoing'] = '01-01-2037';
//            }
            
            $xpath_query = self::_get_xpath_query($arr_filter);
           
            $events = $xml->xpath($xpath_query);

            // Get options from the settings
            $ac_options = get_option('artsopolis_calendar_options');
            
            $settings_display_order = isset($ac_options['settings_display_order']) ?  $ac_options['settings_display_order']: 'START-DATE-ASC';
            $events = self::_sort_events($events, $settings_display_order);
           
            if (empty($ac_options['category'])) {
                return array();
            }
            
            return $events;
        }
        
        private static function _sort_events (&$events , $settings_display_order) {
            switch($settings_display_order) {
                case 'START-DATE-ASC':
                    usort($events, 'ac_sort_by_start_date');
                    break;
                case 'END-DATE-ASC':
                    usort($events, 'ac_sort_by_end_date');
                    break;
                case 'ALPHABETA':
                    usort($events, 'ac_sort_by_alpha');
                    break;
                case 'PRICE-HIGHT':
                    usort($events, 'ac_sort_by_price_hight');
                    break;
                case 'PRICE-SLOW':
                    usort($events, 'ac_sort_by_price_slow');
            }
            
            return $events;

        }
        
        public static function sort_tags(&$tags) {
            usort($tags, 'ac_admin_sub_sort_by_alpha');
            return $tags;
        }
        
        private static function _get_xpath_query($arr_filter) {
            $key_uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $key_lowercase = 'abcdefghijklmnopqrstuvwxyz';
            
            $arr_condition = array();
           
            // Change the operator to OR if we select the this weedken
            if ( isset( $arr_filter['this_weedken'] ) && intval($arr_filter['this_weedken']) == 1 ) {
                $from_date = str_replace(DELIMITER_DATE, '', $arr_filter['from_date']);
                $to_date   = str_replace(DELIMITER_DATE, '', $arr_filter['to_date']);
                $arr_condition[] = " (eventDatesTimes/datetime/date_filter = $from_date or eventDatesTimes/datetime/date_filter = $to_date )";
            } else {
                if (! empty($arr_filter['from_date'])) {
                    $_filter_date_operator = $arr_filter['from_date'] == $arr_filter['to_date'] ? '=' : '>=';
                    $_date = str_replace(DELIMITER_DATE, '', $arr_filter['from_date']);
                    $arr_condition[] = " eventDatesTimes/datetime/date_filter $_filter_date_operator $_date";
                }

                if (! empty($arr_filter['to_date'])) {
                    $_filter_date_operator = $arr_filter['from_date'] == $arr_filter['to_date'] ? '=' : '<=';
                    $_date = str_replace(DELIMITER_DATE, '', $arr_filter['to_date']);
                    $arr_condition[] = " eventDatesTimes/datetime/date_filter $_filter_date_operator $_date";
                }
            }
           
            if ( isset( $arr_filter['first_tab'] ) && $arr_filter['first_tab'] ) {
                $arr_condition[] = " eventDatesTimes/datetime/date_filter <= 20370101 ";
            } else {
                $arr_condition[] = " eventDateEnd = '01-01-2037' "; 
            }
            
            if (! empty($arr_filter['location'])) {
                $arr_condition[] = 'venueCity="'.$arr_filter['location'].'"';
            }
            
            if (!empty($arr_filter['keyword'])) {
                $keyword = strtolower($arr_filter['keyword']);
                $arr_condition[] = ' (contains(translate(eventName, "'.$key_uppercase.'", "'.$key_lowercase.'"), "'.$keyword.'")'
                    . ' or contains(translate(orgName, "'.$key_uppercase.'", "'.$key_lowercase.'"), "'.$keyword.'")'
                    . ' or contains(translate(venueName, "'.$key_uppercase.'", "'.$key_lowercase.'"), "'.$keyword.'")'
                    . ' or contains(translate(venueCity, "'.$key_uppercase.'", "'.$key_lowercase.'"), "'.$keyword.'") ) ';
            }
            
            $category_list_where = '';
            
            // If don't have any category param in the filter, 
            // get the list categories selected in the backend
            $op = get_option('artsopolis_calendar_options'); 
            if (empty($arr_filter['category']) && ! empty($op['category'])) {
                $cat_data = self::_process_category_opt($op['category']);
               $arr_filter['category'] = $cat_data['categories'];
            }
            
            if (! empty($arr_filter['category'])) {
                if (is_array($arr_filter['category'])) {
                    $arr_categories_list = array();
                    foreach ($arr_filter['category'] as $category) {
                        $cat_arr = explode('[+]', $category);
                        $cat_id     = isset($cat_arr[0]) ? $cat_arr[0] : '';
                        $cat_name   = isset($cat_arr[1]) ? $cat_arr[1] : '';
                        $arr_categories_list[] =  ' ( contains(categories, "'.$cat_id.'") and contains(tags, "'.$cat_name.'") ) ';
                    }
                    $arr_condition[] = '('. implode(' or ', $arr_categories_list) . ')';
                } else {
                    $cat_arr = explode('[+]', $arr_filter['category']);
                    if (isset($cat_arr[0])) {
                        $arr_condition[] = '(contains(categories, "'.$cat_arr[0].'")) ';
                    }
                    
                    if (isset($cat_arr[1])) {
                        $arr_condition[] = '(contains(tags, "'.$cat_arr[1].'")) ';
                    }
                }
            }
            
            $xpath_query = 'event';
            if (!empty($arr_condition)) {
                $xpath_query .= '[' . implode(" and ", $arr_condition) . ']';
            }
            
            return $xpath_query;
        }
        
        public static function get_html_list_events($events, $arr_filter, $selected_category = array()) {
            
            $ac_options = get_option('artsopolis_calendar_options');
            if (! $ac_options['feed_url']) {
                $total_event = 0;
                $events = array();
                return '';
            }
            
            $page_size = FRONT_END_PAGE_SIZE;
            $total_event = count($events);
            Artsopolis_Calendar_Shortcode::$_total_event = $total_event;
            $events = array_splice($events, ($arr_filter['page'] - 1) * $page_size, $page_size);
            
            ob_start();
            include dirname(__FILE__) . '/list-events-template.php';
            $html = ob_get_contents();
            ob_end_clean();
            
            return $html;
        }
        
        /* Define callback ajax function */
        public static function ac_get_feed() {
            // The $_REQUEST contains all the data sent via ajax
            if (isset($_REQUEST['page'])) {
                $page         = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
                $from_date    = isset( $_REQUEST['from_date'] ) ? $_REQUEST['from_date'] : '';
                $to_date      = isset( $_REQUEST['to_date'] ) ? $_REQUEST['to_date'] : '';
                $location     = isset( $_REQUEST['location'] ) ? stripslashes($_REQUEST['location']) : '';
                $repagination = isset( $_REQUEST['repagination'] ) ? $_REQUEST['repagination'] : '';
                $keyword      = isset( $_REQUEST['keyword'] ) ? stripslashes($_REQUEST['keyword']) : '';
                $this_weekend = isset( $_REQUEST['this_weekend'] ) ? $_REQUEST['this_weekend'] : '';
                $category     = isset( $_REQUEST['category'] ) ? $_REQUEST['category'] : '';
                $first_tab    = isset( $_REQUEST['first_tab'] ) && $_REQUEST['first_tab'] == 'true';
                
                $arr_filter = array(
                    'page'         => $page,
                    'from_date'    => $from_date,
                    'to_date'      => $to_date,
                    'location'     => $location,
                    'repagination' => $repagination,
                    'keyword'      => $keyword,
                    'this_weedken' => $this_weekend ,
                    'category'     => $category,
                    'first_tab'    => $first_tab
                );
                
                $arr_events = self::get_list_events_data($arr_filter);
                $ac_options = get_option('artsopolis_calendar_options');
                
                // Get the category array for filter
                $category_data = self::_process_category_opt($ac_options['category']);
                $html = self::get_html_list_events($arr_events, $arr_filter, $category_data['keys']);
               
                echo json_encode( array( 
                    'html' => utf8_encode($html),
                    'total' => Artsopolis_Calendar_Shortcode::$_total_event,
                    'page_size' => FRONT_END_PAGE_SIZE,
                ) );
            }
            // Always exit function when you call by the ajax
            exit();
        }
        
        public static function get_event_url($url) {
            if ((! strpos($url, '.gif') && ! strpos($url, '.png') && ! strpos($url, '.jpg')) || strpos($url, 'missing_org') || ! $url)  {
                return plugins_url('/artsopolis-calendar/img/calendar-icon.png');
            }
            
            if (strpos($url, '_medium')) {
                $event_img = str_replace('_medium', '_category', $url);
            } else {
                $ext = substr($url, -4);
                $ext = (strpos($ext, '.') === null ? '.':'').$ext;
                $event_img = str_replace($ext, '', $url). '_category'. $ext;
            }
            return $event_img;
        }
        
        public static function get_featured_events() {
            
            $selected_events = get_option('artsopolis_calendar_featured_events');
            
            if ( empty($selected_events) ) {
                return array();
            }
           
            $xml = @simplexml_load_file(XML_FILE_PATH);
            
            if ( ! $xml ) {
                return array();
            }
            
            $query = array();
            foreach ($selected_events as $event_id) {
                $query[] = 'contains(eventID, "'.$event_id.'")';
            }
            $query = implode(' or ', $query) ;
            
            $events = $xml->xpath('event['.$query.']');
            
            return $events;
            
        } 
        
    }
}

Artsopolis_Calendar_Shortcode::init();