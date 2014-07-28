<?php
/*
  Plugin Name: ReDi Restaurant Reservation
  Plugin URI: http://reservationdiary.eu/eng/reservation-wordpress-plugin/
  Description: ReDi Reservation plugin for Restaurants
  Version: 14.0630
  Author: reservationdiary.eu
  Author URI: http://reservationdiary.eu/
  Text Domain: redi-restaurant-reservation
  Domain Path: /lang

 */
if (!defined('REDI_RESTAURANT_PLUGIN_URL'))
	define('REDI_RESTAURANT_PLUGIN_URL', plugin_dir_url(__FILE__));
if (!defined('REDI_RESTAURANT_TEMPLATE'))
	define('REDI_RESTAURANT_TEMPLATE', plugin_dir_path(__FILE__).'templates'.DIRECTORY_SEPARATOR);
if (!defined('ID'))
	define('ID', 'ID');
require_once('redi.php');


if (!class_exists('ReDiRestaurantReservation'))
{
    class Report{
        const Full ='Full';
        const None ='None';
        const Single ='Single';
    }
    class EmailFrom{
        const ReDi = 'ReDi';
        const WordPress = 'WordPress';
        const Disabled = 'Disabled';
    }
    class EmailContentType{
        const Canceled = 'Canceled';
        const Confirmed = 'Confirmed';
    }

	class ReDiRestaurantReservation
	{
		public $version = '14.0630';
		/**
		 * @var string The options string name for this plugin
		 */
		private $optionsName = 'wp_redi_restaurant_options';
		private static $name = 'REDI_RESTAURANT';
		private $options = array ();
		private $ApiKey;
		private $redi;
		private $weekday = array ('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

		function filter_timeout_time($time) {
			$time = 30; //new number of seconds
			return $time;
		}
		
		public function __construct() {
			$this->_name = self::$name;
			//Initialize the options
			$this->get_options();

			$this->ApiKey = isset( $this->options[ REDI_APIKEY ] ) ? $this->options[ REDI_APIKEY ] : null;
			$this->redi   = new Redi( $this->ApiKey );
			//Actions
			add_action( 'init', array( &$this, 'init_sessions' ) );
			add_action( 'admin_menu', array( &$this, 'redi_restaurant_admin_menu_link' ) );

			$this->page_title = 'Reservation';
			$this->content    = '[redirestaurant]';
			$this->page_name  = $this->_name;
			$this->page_id    = '0';

			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
			register_uninstall_hook( __FILE__, array( 'ReDiRestaurantReservation', 'uninstall' ) );

			add_action( 'wp_ajax_nopriv_redi_restaurant-submit', array( &$this, 'redi_restaurant_ajax' ) );
			add_action( 'wp_ajax_redi_restaurant-submit', array( &$this, 'redi_restaurant_ajax' ) );
			add_filter( 'http_request_timeout', array( &$this,'filter_timeout_time'));
			add_shortcode( 'redirestaurant', array( $this, 'shortcode' ) );
		}

		function ReDiRestaurantReservation() {
			$this->__construct();
		}

		//TODO: better version check
		function plugin_get_version()
		{
			$plugin_data = get_plugin_data(__FILE__);
			$plugin_version = $plugin_data['Version'];

			return $plugin_version;
		}

		/**
		 * Retrieves the plugin options from the database.
		 * @return array
		 */
		function get_options()
		{
			if (!$options = get_option($this->optionsName))
				update_option($this->optionsName, $options);
			$this->options = $options;
		}

		private function register()
		{
			//Gets email and sitename from config
			$new_account = $this->redi->createUser(array ('Email' => get_option('admin_email')));

			if (isset($new_account[REDI_APIKEY]) && !empty($new_account[REDI_APIKEY]))
			{
				$this->ApiKey = $this->options[REDI_APIKEY] = $new_account[REDI_APIKEY];
				$this->redi->setApiKey($this->options[REDI_APIKEY]);
				$place = $this->redi->createPlace(array (
				                                        'place' => array (
					                                        'Name' => get_bloginfo('name'),//get from site name
					                                        'City' => 'city',
					                                        'Country' => 'country',
					                                        'Address' => 'Address line 1',
					                                        'Email' => get_option('admin_email'),
					                                        'EmailCC' => '',
					                                        'Phone' => '[areacode] [number]',
					                                        'WebAddress' => get_option('siteurl'),
					                                        'Lang' => str_replace('_', '-', get_locale()),
					                                        'MinTimeBeforeReservation' => 24, // hour
					                                        'DescriptionShort' => get_option('blogdescription'),
					                                        'DescriptionFull' => '',
					                                        'Catalog' => true
				                                        )
				                                  ));

				if(isset($place['Error']))
					return $place;

                $placeID = (int)$place[ID];

				$category = $this->redi->createCategory($placeID,
					array ('category' => array ('Name' => 'Restaurant')));

				if(isset($category['Error']))
					return $category;

                $categoryID = (int)$category[ID];
				$service = $this->redi->createService($categoryID,
					array ('service' => array ('Name' => 'Person', 'Quantity' => 10)));

				if(isset($service['Error']))
					return $service;

				foreach ($this->weekday as $value)
					$times[$value] = array ('OpenTime' => '12:00', 'CloseTime' => '00:00');
				$this->redi->setServiceTime($categoryID, $times);

				$this->options['serviceID'] = $serviceID = (int)$service[0]->ID;
				$this->saveAdminOptions();
			}


			return $new_account;
		}

		/**
		 * Saves the admin options to the database.
		 */
		function saveAdminOptions()
		{
			return update_option($this->optionsName, $this->options);
		}

		function display_errors( $errors, $admin = false ) {
			if ( isset( $errors['Error'] ) ) {
				foreach ( (array) $errors['Error'] as $error ) {
					echo '<div class="error"><p>' . $error . '</p></div>';
				}
			}
			//WP-errors
			if ( isset( $errors['Wp-Error'] ) && $admin ) {
				foreach ( (array) $errors['Wp-Error'] as $error_key => $error ) {
					foreach ( (array) $error as $err ) {
						echo '<div class="error"><p>' . $error_key . ' : ' . $err . '</p></div>';
					}
				}
			}
		}

		/**
		 * Adds settings/options page
		 */
		function redi_restaurant_admin_options_page()
		{
			$errors = array();

			if ($this->ApiKey == NULL) /// TODO: move to install
			{
				$return = $this->register();
				$this->display_errors($return, true);
			}

			if ($this->ApiKey == NULL)
			{

				$errors['Error'] = array(
					__('ReDi Restaurant Reservation plugin could not get an API key from the reservationdiary.eu server when it activated.' .
						'<br/> You can try to fix this by going to the ReDi Restaurant Reservation "options" page. ' .
						'<br/>This will cause ReDi Restaurant Reservation plugin to retry fetching an API key for you. ' .
						'<br/>If you keep seeing this error it usually means that server where you host your web site can\'t connect to our reservationdiary.eu server. ' .
						'<br/>You can try asking your WordPress host to allow your WordPress server to connect to api.reservationdiary.eu' .
						'<br/>In case you can not solve this problem yourself, please contact us directly by <a href="mailo:info@reservationdiary.eu">info@reservationdiary.eu</a>', 'redi-restaurant-reservation'));
				$this->display_errors($errors, true);
				die;
			}
			$places = $this->redi->getPlaces();

			if ( isset( $places['Error'] ) ) {
				$this->display_errors( $places, true );
				die;
			}
			$placeID = $places[0]->ID;

			$categories = $this->redi->getPlaceCategories($placeID);

			$serviceID = $this->options['serviceID'];

			$categoryID =  $categories[0]->ID;

			if ( isset( $_POST['action'] ) && $_POST['action'] == 'cancel' ) {
				if ( isset( $_POST['id'] ) && ( (int) $_POST['id'] ) > 0 ) {
					$params = array(
						'ID'          => $_POST['id'],
						'Lang'        => str_replace( '_', '-', get_locale() ),
						'Reason'      => urlencode( mb_substr( $_POST['Reason'], 0, 250 ) ),
						'CurrentTime' => urlencode( date( 'Y-m-d H:i', current_time( 'timestamp' ) ) ),
						'Version'     => urlencode( self::plugin_get_version() )
					);
					if ( $this->options['EmailFrom'] == EmailFrom::Disabled || $this->options['EmailFrom'] == EmailFrom::WordPress ) {
						$params['DontNotifyClient'] = 'true';
					}
					$cancel = $this->redi->cancelReservation( $params );
					if ( $this->options['EmailFrom'] == EmailFrom::WordPress && ! isset( $cancel['Error'] ) ) {
						//call api for content
						$emailContent = $this->redi->getEmailContent(
							(int) $cancel['ID'],
							EmailContentType::Canceled,
							array(
								"Lang" => str_replace( '_', '-', $_POST['lang'] )
							)
						);

						//send
						if ( ! isset( $emailContent['Error'] ) ) {
							wp_mail( $emailContent['To'], $emailContent['Subject'], $emailContent['Body'], array( 'Content-Type: text/html; charset=UTF-8' ) );
						}
					}
					if ( isset( $cancel['Error'] ) ) {
						$errors[] = $cancel['Error'];
					}else {
						$cancel_success = __( 'Reservation has been successfully canceled.', 'redi-restaurant-reservation' );
					}

				} else {
					$errors[] = __( 'Reservation number is required', 'redi-restaurant-reservation' );
				}
			}
			$settings_saved = false;
			if (isset($_POST['submit']))
			{
				$form_valid = true;
				//validation
				$minPersons = (int)$_POST['MinPersons'];
				$maxPersons = (int)$_POST['MaxPersons'];
                $largeGroupsMessage = $_POST['LargeGroupsMessage'];
                $emailFrom = $_POST['EmailFrom'];
				$report = isset( $_POST['Report'] ) ? $_POST['Report'] : Report::Full;
				$maxTime = $_POST['MaxTime'];

				if($minPersons >= $maxPersons)
				{
					$errors[] = __('Min Persons should be lower than Max Persons', 'redi-restaurant-reservation');
					$form_valid = false;
				}

				$reservationTime = (int)$_POST['ReservationTime'];
				if($reservationTime <= 0)
				{
					$errors[] = __('Reservation time should be greater than 0', 'redi-restaurant-reservation');
					$form_valid = false;
				}
				$place = array(
					'place' => array(
						'Name'                     => $_POST['Name'],
						'City'                     => $_POST['City'],
						'Country'                  => $_POST['Country'],
						'Address'                  => $_POST['Address'],
						'Email'                    => $_POST['Email'],
						'EmailCC'                  => $_POST['EmailCC'],
						'Phone'                    => $_POST['Phone'],
						'WebAddress'               => $_POST['WebAddress'],
						'Lang'                     => $_POST['Lang'],
						'DescriptionShort'         => $_POST['DescriptionShort'],
						'DescriptionFull'          => $_POST['DescriptionFull'],
						'MinTimeBeforeReservation' => $_POST['MinTimeBeforeReservation'],
						'Catalog'                  => (int) $_POST['Catalog'],
						'DateFormat'               => $_POST['DateFormat'],
						'MaxTimeBeforeReservation' => $maxTime,
						'Version'                  => $this->version
					)
				);

				$serviceTimes = self::GetServiceTimes();

				$this->options['Thanks'] = isset($_POST['Thanks']) ? (int)$_POST['Thanks'] : 0;
				$this->options['TimePicker'] = isset($_POST['TimePicker']) ? $_POST['TimePicker'] : null;
                $this->options['AlternativeTimeStep'] = isset($_POST['AlternativeTimeStep']) ? $_POST['AlternativeTimeStep'] : 30;

				$services = (int)$_POST['services'];

				$this->options['services'] = $services;
				$this->options['MinTimeBeforeReservation'] = $_POST['MinTimeBeforeReservation'];
				$this->options['DateFormat'] = $_POST['DateFormat'];
				$this->options['ReservationTime'] = $reservationTime;

				for($i = 1; $i != CUSTOM_FIELDS; $i++)
				{
					$field_name = 'field_'.$i.'_name';
					$field_type = 'field_'.$i.'_type';
					$field_required = 'field_'.$i.'_required';
					$field_message = 'field_'.$i.'_message';

					if(isset($_POST[$field_name]))
					{
						$this->options[$field_name] = $_POST[$field_name];
					}

					if(isset($_POST[$field_type]))
					{
						$this->options[$field_type] = $_POST[$field_type];
					}

					$this->options[$field_required] = (isset($_POST[$field_required]) && $_POST[$field_required] === 'on');

					if(isset($_POST[$field_message]))
					{
						$this->options[$field_message] = $_POST[$field_message];
					}
				}

				if($form_valid)
				{
                    $settings_saved = true;
					$this->options['MinPersons'] = $minPersons;
					$this->options['MaxPersons'] = $maxPersons;
                    $this->options['LargeGroupsMessage'] = $largeGroupsMessage;
                    $this->options['EmailFrom'] = $emailFrom;
                    $this->options['Report'] = $report;
					$this->options['MaxTime'] = $maxTime;
					
					$placeID = $_POST['Place'];
					$categories = $this->redi->getPlaceCategories($placeID);
					if(isset($categories['Error']))
					{
						$errors[] = $categories['Error'];
						$settings_saved = false;
					}
					$categoryID = $categories[0]->ID;
					$this->options['OpenTime'] = $_POST['OpenTime'];
					$this->options['CloseTime'] = $_POST['CloseTime'];

					$getServices = $this->redi->getServices($categoryID);
					if(isset($getServices['Error']))
					{
						$errors[] = $getServices['Error'];
						$settings_saved = false;
					}
					if (count($getServices) != $services)
					{
						if (count($getServices) > $services)
						{
							//delete
							$diff = count($getServices) - $services;
							$ids = array ();

							$removeServices = array_slice($getServices, 0, $diff);
							foreach ($removeServices AS $service)
								$ids[] = $service->ID;

							$cancel = $this->redi->deleteServices($ids);
							if(isset($cancel['Error']))
							{
								$errors[] = $cancel['Error'];
								$settings_saved = false;
							}
							$cancel = array();
						}
						else
						{
							//add
							$diff = $services - count($getServices);

							$cancel = $this->redi->createService($categoryID,
								array (
									'service' => array (
										'Name' => 'Person',
										'Quantity' => $diff
									)
								));
							if(isset($cancel['Error']))
							{
								$errors[] = $cancel['Error'];
								$settings_saved = false;
							}
							$cancel = array();
						}
					}

					$this->saveAdminOptions();

					if (is_array($serviceTimes) && count($serviceTimes))
					{
						$cancel = $this->redi->setServiceTime($categoryID, $serviceTimes);
						if(isset($cancel['Error']))
						{
							$errors[] = $cancel['Error'];
							$settings_saved = false;
						}
						$cancel = array();
					}
					$cancel = $this->redi->setPlace($placeID, $place);
					if(isset($cancel['Error']))
					{
						$errors[] = $cancel['Error'];
						$settings_saved = false;
					}
					$cancel = array();
				}
				else
				{
					//Send every setting back to form so user can correct it

				}

				$places = $this->redi->getPlaces();
				if(isset($places['Error']))
				{
					$errors[] = $places['Error'];
					$settings_saved = false;
				}
			}

			$this->options = get_option($this->optionsName);

			if ( $settings_saved || ! isset( $_POST['submit'] ) ) {
				$thanks              = $this->GetOption( 'Thanks', 0 );
				$timepicker          = $this->GetOption( 'TimePicker' );

				$minPersons          = $this->GetOption( 'MinPersons', 1 );
				$maxPersons          = $this->GetOption( 'MaxPersons', 10 );
				$alternativeTimeStep = $this->GetOption( 'AlternativeTimeStep', 30 );
				$largeGroupsMessage  = $this->GetOption( 'LargeGroupsMessage', '' );
				$emailFrom           = $this->GetOption( 'EmailFrom', EmailFrom::ReDi );
				$report              = $this->GetOption( 'Report', Report::Full );
				$maxTime             = $this->GetOption( 'MaxTime', 1 );
			}
			if(!$settings_saved && isset($_POST['submit']))
			{
				$timepicker         = $_POST['TimePicker'];
				$alternativeTimeStep = $_POST['AlternativeTimeStep'];
			}

			for($i = 1; $i != CUSTOM_FIELDS; $i++)
			{
				$field_name = 'field_'.$i.'_name';
				$field_type = 'field_'.$i.'_type';
				$field_required = 'field_'.$i.'_required';
				$field_message = 'field_'.$i.'_message';

				$$field_name = self::GetOption($field_name);
				$$field_type = self::GetOption($field_type);
				$$field_required = self::GetOption($field_required);
				$$field_message = self::GetOption($field_message);
			}

			//if settings are saved or this is first time load
			if (!isset($_POST['submit']) || $settings_saved)
			{
				$getServices = $this->redi->getServices($categoryID);
				if(isset($getServices['Error']))
				{
					$errors[] = $getServices['Error'];
				}

				$reservationTime = $this->getReservationTime();
			}

			require_once(REDI_RESTAURANT_TEMPLATE.'admin.php');
			require_once(REDI_RESTAURANT_TEMPLATE.'basicpackage.php');
		}

		private function GetOption( $name, $default = null ) {
			return isset( $this->options[ $name ] ) ? $this->options[ $name ] : $default;
		}

		function GetServiceTimes()
		{
			$serviceTimes = array();
			foreach ($_POST['OpenTime'] as $key => $value)
			{
				if (self::set_and_not_empty($value))
				{
					$serviceTimes[$key]['OpenTime'] = $value;
				}
			}
			foreach ($_POST['CloseTime'] as $key => $value)
			{
				if (self::set_and_not_empty($value))
				{
					$serviceTimes[$key]['CloseTime'] = $value;
				}
			}
			return  $serviceTimes;
		}

		function ajaxed_admin_page($placeID, $categoryID, $settings_saved =false)
		{
			require_once(plugin_dir_path(__FILE__).'languages.php');
			$places = $this->redi->getPlaces();
			$getServices = $this->redi->getServices($categoryID);

			if (!isset($_POST['submit']) || $settings_saved)
			{

				$serviceTimes = $this->redi->getServiceTime($categoryID); //goes to template 'admin'
				$serviceTimes = json_decode(json_encode($serviceTimes), true);
				$place = $this->redi->getPlace($placeID); //goes to template 'admin'

			}
			else
			{
				$place = array(
					'Name' => $_POST['Name'],
					'City' => $_POST['City'],
					'Country' => $_POST['Country'],
					'Address' => $_POST['Address'],
					'Email' => $_POST['Email'],
					'EmailCC' => $_POST['EmailCC'],
					'Phone' => $_POST['Phone'],
					'WebAddress' => $_POST['WebAddress'],
					'Lang' => $_POST['Lang'],
					'DescriptionShort' => $_POST['DescriptionShort'],
					'DescriptionFull' => $_POST['DescriptionFull'],
					'MinTimeBeforeReservation' => $_POST['MinTimeBeforeReservation'],
					'Catalog' => (int)$_POST['Catalog'],
					'DateFormat' =>$_POST['DateFormat']
				);
				$serviceTimes = self::GetServiceTimes();
			}
			require_once(REDI_RESTAURANT_TEMPLATE.'admin_ajaxed.php');
		}

		function init_sessions()
		{
			if (!session_id())
				session_start();

			if (function_exists('load_plugin_textdomain'))
			{
				load_plugin_textdomain( 'redi-restaurant-reservation', false, 'redi-restaurant-reservation/lang');
				load_plugin_textdomain( 'redi-restaurant-reservation-errors', false, 'redi-restaurant-reservation/lang');
			}

		}
		/**
		 * @desc Adds the options subpanel
		 */
		function redi_restaurant_admin_menu_link()
		{
			add_options_page('Redi Restaurant Reservation',
				'Redi Restaurant Reservation',
				'manage_options',
				'options_page_slug',
				array (&$this, 'redi_restaurant_admin_options_page'));
		}

		static function install()
		{
			//register is here
		}

		public function activate()
		{
			delete_option($this->_name.'_page_title');
			add_option($this->_name.'_page_title', $this->page_title, '', 'yes');

			delete_option($this->_name.'_page_name');
			add_option($this->_name.'_page_name', $this->page_name, '', 'yes');

			delete_option($this->_name.'_page_id');
			add_option($this->_name.'_page_id', $this->page_id, '', 'yes');

			$the_page = get_page_by_title($this->page_title);

			if (!$the_page)
			{
				// Create post object
				$_p = array ();
				$_p['post_title'] = $this->page_title;
				$_p['post_content'] = $this->content;
				$_p['post_status'] = 'publish';
				$_p['post_type'] = 'page';
				$_p['comment_status'] = 'closed';
				$_p['ping_status'] = 'closed';
				$_p['post_category'] = array (1); // the default 'Uncategorized'
				// Insert the post into the database
				$this->page_id = wp_insert_post($_p);
			}
			else
			{
				// the plugin may have been previously active and the page may just be trashed...
				$this->page_id = $the_page->ID;

				//make sure the page is not trashed...
				$the_page->post_status = 'publish';
				$this->page_id = wp_update_post($the_page);
			}

			delete_option($this->_name.'_page_id');
			add_option($this->_name.'_page_id', $this->page_id);

			if ($this->ApiKey == NULL) // TODO: move to install
				$this->register();

		}

		private static function set_and_not_empty($value)
		{
			return (isset($value) && !empty($value));
		}

		public function deactivate()
		{
			$this->deletePage();
			$this->deleteOptions();
		}

		public static function uninstall()
		{
			self::deletePage(TRUE);
			self::deleteOptions();
		}

		private function deletePage($hard = FALSE)
		{
			$id = get_option(self::$name.'_page_id');
			if ($id && $hard == TRUE)
				wp_delete_post($id, TRUE);
			elseif ($id && $hard == FALSE)
				wp_delete_post($id);
		}

		private function deleteOptions()
		{
			delete_option(self::$name.'_page_title');
			delete_option(self::$name.'_page_name');
			delete_option(self::$name.'_page_id');
		}

		function getCalendarDateFormat($format)
		{
			switch ($format)
			{
				case 'MM-dd-yyyy':
					return 'mm-dd-yy';

				case 'dd-MM-yyyy':
					return 'dd-mm-yy';

				case 'yyyy.MM.dd':
					return 'yy.mm.dd';

				case 'MM.dd.yyyy':
					return 'mm.dd.yy';

				case 'dd.MM.yyyy':
					return 'dd.mm.yy';

				case 'yyyy/MM/dd':
					return 'yy/mm/dd';

				case 'MM/dd/yyyy':
					return 'mm/dd/yy';

				case 'dd/MM/yyyy':
					return 'dd/mm/yy';
			}

			return 'yy-mm-dd';
		}

		function getPHPDateFormat($format)
		{
			switch ($format)
			{
				case 'MM-dd-yyyy':
					return 'm-d-Y';

				case 'dd-MM-yyyy':
					return 'd-m-Y';

				case 'yyyy.MM.dd':
					return 'Y.m.d';

				case 'MM.dd.yyyy':
					return 'm.d.Y';

				case 'dd.MM.yyyy':
					return 'd.m.Y';

				case 'yyyy/MM/dd':
					return 'Y/m/d';

				case 'MM/dd/yyyy':
					return 'm/d/Y';

				case 'dd/MM/yyyy':
					return 'd/m/Y';
			}

			return 'Y-m-d';
		}

		public function shortcode($atts)
		{
			if(is_array($atts)){
				$this->options = array_merge($this->options, $atts);
			}
            ob_start();
            wp_enqueue_script('jquery');
            wp_register_style('jquery_ui', null, array ('jquery'));
            wp_enqueue_style('jquery_ui');

            wp_register_style('jquery-ui-custom-style',
                    REDI_RESTAURANT_PLUGIN_URL.'/css/custom-theme/jquery-ui-1.8.18.custom.css');
            wp_enqueue_style('jquery-ui-custom-style');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_register_script('datetimepicker',
                    REDI_RESTAURANT_PLUGIN_URL.'/lib/datetimepicker/js/jquery-ui-timepicker-addon.js',
                    array ('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker'));
            wp_enqueue_script('datetimepicker');

            wp_register_script('datetimepicker-lang',REDI_RESTAURANT_PLUGIN_URL.'/lib/datetimepicker/js/jquery.ui.i18n.all.min.js');
            wp_enqueue_script('datetimepicker-lang');

            wp_register_script('timepicker-lang',REDI_RESTAURANT_PLUGIN_URL.'/lib/timepicker/i18n/jquery-ui-timepicker.all.lang.js');
            wp_enqueue_script('timepicker-lang');

            wp_register_script('restaurant', REDI_RESTAURANT_PLUGIN_URL.'js/restaurant.js', array ('jquery'));

            wp_localize_script('restaurant',
                'redi_restaraurant_reservation',
                array ( // URL to wp-admin/admin-ajax.php to process the request
                      'ajaxurl' => admin_url('admin-ajax.php'),
                      'id_missing' => __('Reservation number can\'t be empty', 'redi-restaurant-reservation'),
                      'name_missing'  => __('Name can\'t be empty', 'redi-restaurant-reservation'),
                      'email_missing' => __('Email can\'t be empty', 'redi-restaurant-reservation'),
                      'phone_missing' => __('Phone can\'t be empty', 'redi-restaurant-reservation'),
                      'reason_missing' => __('Reason can\'t be empty', 'redi-restaurant-reservation'),
                ));
            wp_enqueue_script('restaurant');

            wp_register_style('redi-restaurant', REDI_RESTAURANT_PLUGIN_URL.'/css/restaurant.css');
            wp_enqueue_style('redi-restaurant');
          //  $persons = 2; //min can be bigger than 2
			if ( $this->ApiKey == null ) {

				$this->display_errors(array('Error'=> '<div class="error"><p>' . __( 'Online reservation service is not available at this time. Try again later or contact us directly.', 'redi-restaurant-reservation' ) . '</p></div>'));
				return;
			}
            //places
            $places = $this->redi->getPlaces();
			if ( isset($places['Error']) ) {

				$this->display_errors($places);
				return;
			}
            $placeID = $places[0]->ID;


            $time_format = get_option('time_format');
            $date_format_setting = $this->options['DateFormat'];

			$calendar_date_format = $this->getCalendarDateFormat($date_format_setting);
			$date_format = $this->getPHPDateFormat($date_format_setting);

            $MinTimeBeforeReservation = (int)($this->options['MinTimeBeforeReservation'] > 0 ? $this->options['MinTimeBeforeReservation'] : 0) + 1;

			$reservationStartTime = strtotime('+'.$MinTimeBeforeReservation.' hour', current_time('timestamp'));
            $startDate = date($date_format, $reservationStartTime);
            $startDateISO = date('Y-m-d', $reservationStartTime);
			$startTime = mktime(date("G", $reservationStartTime), 0, 0, 0, 0, 0);


			$minPersons         = $this->GetOption( 'MinPersons', 1 );
			$maxPersons         = $this->GetOption( 'MaxPersons', 10 );
			$largeGroupsMessage = $this->GetOption( 'LargeGroupsMessage', '' );
			$emailFrom          = $this->GetOption( 'EmailFrom', EmailFrom::ReDi );
			$report             = $this->GetOption( 'Report', Report::Full );
			$maxTime            = $this->GetOption( 'MaxTime', 1 );
			$thanks             = $this->GetOption('Thanks');

            for($i = 1; $i != CUSTOM_FIELDS; $i++)
            {
                $field_name = 'field_'.$i.'_name';
                $field_type = 'field_'.$i.'_type';
                $field_required = 'field_'.$i.'_required';
                $field_message = 'field_'.$i.'_message';

                if(isset($this->options[$field_name]))
                {
                    $$field_name = $this->options[$field_name];
                }

                if(isset($this->options[$field_type]))
                {
                    $$field_type = $this->options[$field_type];
                }

                if(isset($this->options[$field_required]))
                {
                    $$field_required = $this->options[$field_required];
                }

                if(isset($this->options[$field_message]))
                {
                    $$field_message = $this->options[$field_message];
                }
            }

			$time_format_hours = self::dropdown_time_format();

			$timepicker          = $this->GetOption( 'timepicker', $this->GetOption( 'TimePicker' ) );

			$alternativeTimeStep = $this->GetOption( 'AlternativeTimeStep', 30 );
            require_once(REDI_RESTAURANT_TEMPLATE.'frontend.php');
            $out = ob_get_contents();

            ob_end_clean();
            return $out;
		}

		function dropdown_time_format()
		{
			$wp_time_format = get_option('time_format');
			$wp_time_format_array = str_split($wp_time_format);
			foreach($wp_time_format_array as $index=>$format_char) // some users have G \h i \m\i\n
			{
				if($format_char ==='\\')
				{
					$wp_time_format_array[$index] = '';
					if(isset($wp_time_format_array[$index + 1]))
					{
						$wp_time_format_array[$index + 1] = '';
					}
				}
			}
			$wp_time_format = implode('', $wp_time_format_array);
			$is_am_pm = strpos($wp_time_format, 'g');
			$is_am_pm_lead_zero = strpos($wp_time_format, 'h');

			$is_24 = strpos($wp_time_format, 'G');
			$is_24_lead_zero = strpos($wp_time_format, 'H');

			if($is_am_pm !== FALSE || $is_am_pm_lead_zero !== FALSE)
			{
				$a = stripos($wp_time_format, 'a');
				$am_text = '';
				if($a !== FALSE)
				{
					$am_text =$wp_time_format[$a];
				}
				if($is_am_pm !== FALSE)
				{
					return $wp_time_format[$is_am_pm].' '.$am_text;
				}
				if( $is_am_pm_lead_zero !== FALSE)
				{
					return $wp_time_format[$is_am_pm_lead_zero].' '.$am_text;
				}
			}
			if($is_24 !== FALSE)
			{
				return $wp_time_format[$is_24];
			}
			if($is_24_lead_zero !== FALSE)
			{
				return $wp_time_format[$is_24_lead_zero];
			}
			return 'H'; //if no time format found use 24 h with lead zero
		}

        function redi_restaurant_ajax()
        {
            if (isset($_POST['placeID']))
            {
                $placeID    = (int) $_POST['placeID'];
                $categories = $this->redi->getPlaceCategories($placeID);
                if(isset($categories['Error']))
                {
                    echo json_encode($categories);
                    die;
                }
                $categoryID = $categories[0]->ID;
                
            }
            switch ($_POST['get'])
            {
                case 'step1':
                    // convert date to array

                    $date = date_parse($_POST['startDateISO'].' '.$_POST['startTime']);

                    if ($date['error_count'] > 0)
                    {
                        echo json_encode(array('Error' => __('Selected date or time is not valid.', 'redi-restaurant-reservation')));
                        die;
                    }

                    $startTimeStr = $date['year'].'-'.$date['month'].'-'.$date['day'].' '.$date['hour'].':'.$date['minute'];

					$persons = (int) $_POST['persons'];
                    // convert to int
                    $startTimeInt = strtotime($startTimeStr, 0);

                    // calculate end time
                    $endTimeInt = strtotime('+'.$this->getReservationTime($persons).'minutes', $startTimeInt);

                    // format to ISO
                    $startTimeISO   = date('Y-m-d H:i', $startTimeInt);
                    $endTimeISO     = date('Y-m-d H:i', $endTimeInt);
                    $currentTimeISO = date('Y-m-d H:i', current_time('timestamp'));

                    $params = array(
                        'StartTime'    => urlencode($startTimeISO),
                        'EndTime'      => urlencode($endTimeISO),
                        'Quantity'     => $persons,
                        'Alternatives' => 2,
                        'Lang'         => str_replace('_', '-', $_POST['lang']),
                        'CurrentTime'  => urlencode($currentTimeISO),
                        'AlternativeTimeStep' => self::getAlternativeTimeStep($persons)
                    );

                    //get first category on selected place
                    $categories = $this->redi->getPlaceCategories($placeID);
                    if(isset($categories['Error'])){
	                    $categories['Error'] = __( $categories['Error'], 'redi-restaurant-reservation-errors' );
                        echo json_encode($categories);
                        die;
                    }
                    $category   = $categories[0];

                    $query = $this->redi->query($category->ID, $params);

                    $time_format = get_option('time_format');

                    if (!isset($query['Error'])){
                        unset($query['debug']);
                        foreach ($query as $q)
                        {
                            $q->Select       = ($startTimeISO == $q->StartTime && $q->Available);
                            $q->StartTimeISO = $q->StartTime;
                            $q->StartTime    = date($time_format, strtotime($q->StartTime));
                            $q->EndTime      = date($time_format, strtotime($q->EndTime));
                        }
                    }
					else{
						$query['Error'] = __( $query['Error'], 'redi-restaurant-reservation-errors' );
					}
                    echo json_encode($query);
                    break;

                case 'step3':

	                $persons = (int) $_POST['persons'];
                    $startTimeStr = $_POST['startTime'];

                    // convert to int
                    $startTimeInt = strtotime($startTimeStr, 0);

                    // calculate end time
                    $endTimeInt = strtotime('+'.$this->getReservationTime($persons).'minutes', $startTimeInt);

                    // format to ISO
                    $startTimeISO   = date('Y-m-d H:i', $startTimeInt);
                    $endTimeISO     = date('Y-m-d H:i', $endTimeInt);
                    $currentTimeISO = date('Y-m-d H:i', current_time('timestamp'));
                    $comment        = '';
                    for ($i = 1; $i != CUSTOM_FIELDS; $i++)
                    {
                        if (isset($_POST['field_'.$i]))
                        {

                            $field_type = 'field_'.$i.'_type';

                            if (isset($this->options[$field_type]) && $this->options[$field_type] === 'checkbox')
                            {
                                $comment .= $this->options['field_'.$i.'_name'].': ';
                                $comment .= ($_POST['field_'.$i] === 'on') ? __('Yes', 'redi-restaurant-reservation') : __('No', 'redi-restaurant-reservation');
                                $comment .= '<br/>';
                            }
                            else
                            {
                                if (!empty($_POST['field_'.$i]))
                                {
                                    $comment .= $this->options['field_'.$i.'_name'].': ';
                                    $comment .= $_POST['field_'.$i].'<br/>';
                                }
                            }
                        }
                    }
                    if (!empty($comment))
                    {
                        $comment .= '<br/>';
                    }
                    $comment .= mb_substr( $_POST['UserComments'], 0, 250 );

                    $params = array(
                        'reservation' => array(
                            'StartTime'    => $startTimeISO,
                            'EndTime'      => $endTimeISO,
                            'Quantity'     => $persons,
                            'UserName'     => $_POST['UserName'],
                            'UserEmail'    => $_POST['UserEmail'],
                            'UserComments' => $comment,
                            'UserPhone'    => $_POST['UserPhone'],
                            'Name'         => 'Person',
                            'Lang'         => str_replace('_', '-', $_POST['lang']),
                            'CurrentTime'  => $currentTimeISO,
                            'Version'      => $this->version
                        )
                    );
	                if ( $this->options['EmailFrom'] == EmailFrom::Disabled || $this->options['EmailFrom'] == EmailFrom::WordPress ) {
		                $params['reservation']['DontNotifyClient'] = 'true';
	                }
	                $reservation = $this->redi->createReservation( $categoryID, $params );

	                if ( $this->options['EmailFrom'] == EmailFrom::WordPress && ! isset( $reservation['Error'] ) ) {
		                //call api for content
		                $emailContent = $this->redi->getEmailContent(
			                (int) $reservation['ID'],
			                EmailContentType::Confirmed,
			                array(
				                "Lang" => str_replace( '_', '-', $_POST['lang'] )
			                )
		                );

		                //send
		                if ( ! isset( $emailContent['Error'] ) ) {
			                wp_mail( $emailContent['To'], $emailContent['Subject'], $emailContent['Body'], array( 'Content-Type: text/html; charset=UTF-8' ) );
		                }
	                }
                    echo json_encode($reservation);
                    break;

                case 'get_place':
                    self::ajaxed_admin_page($placeID, $categoryID, true);
                    break;

	            case 'cancel':
		            $params = array(
			            'ID'          => (int) $_POST['ID'],
			            'Email'       => urlencode( $_POST['Email'] ),
			            'Reason'      => urlencode( mb_substr( $_POST['Reason'], 0, 250 ) ),
			            "Lang"        => str_replace( '_', '-', $_POST['lang'] ),
			            'CurrentTime' => urlencode( date( 'Y-m-d H:i', current_time( 'timestamp' ) ) ),
			            'Version'     => urlencode( self::plugin_get_version() )
		            );
		            if ( $this->options['EmailFrom'] == EmailFrom::Disabled || $this->options['EmailFrom'] == EmailFrom::WordPress ) {
			            $params['DontNotifyClient'] = 'true';
		            }
		            $cancel = $this->redi->cancelReservationByClient( $params );
		            if ( $this->options['EmailFrom'] == EmailFrom::WordPress && ! isset( $cancel['Error'] ) ) {
			            //call api for content
			            $emailContent = $this->redi->getEmailContent(
				            (int) $cancel['ID'],
				            EmailContentType::Canceled,
				            array(
					            "Lang" => str_replace( '_', '-', $_POST['lang'] )
				            )
			            );

			            //send
			            if ( ! isset( $emailContent['Error'] ) ) {
				            wp_mail( $emailContent['To'], $emailContent['Subject'], $emailContent['Body'], array( 'Content-Type: text/html; charset=UTF-8' ) );
			            }
		            }
		            echo json_encode( $cancel );

		            break;
            }

            die;
        }

		private function getAlternativeTimeStep($persons = 0)
        {
            $filename =  plugin_dir_path(__FILE__).'alternativetimestep.json';

            if (file_exists($filename) && $persons)
            {
                $json = json_decode(file_get_contents($filename), TRUE);
                if($json !== NULL)
                {
                    if(array_key_exists($persons, $json))
                    {
                        return (int)$json[$persons];
                    }
                }
            }

            if (isset($this->options['AlternativeTimeStep']) && $this->options['AlternativeTimeStep'] > 0)
            {
                return (int) $this->options['AlternativeTimeStep'];
            }

            return 30;
        }

        private function getReservationTime($persons = 0)
        {
			$filename =  plugin_dir_path(__FILE__).'reservationtime.json';
			
			if (file_exists($filename) && $persons)
			{
				$json = json_decode(file_get_contents($filename), TRUE);
				if($json !== NULL)
				{
					if(array_key_exists($persons, $json))
					{
						return (int)$json[$persons];
					}
				}
			}

            if (isset($this->options['ReservationTime']) && $this->options['ReservationTime'] > 0)
            {
                return (int) $this->options['ReservationTime'];
            }
            return 3*60;
        }
	}
}
new ReDiRestaurantReservation();

register_activation_hook(__FILE__, array ('ReDiRestaurantReservation', 'install'));