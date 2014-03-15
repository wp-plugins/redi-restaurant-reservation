<?php
/*
  Plugin Name: ReDi Restaurant Reservation
  Plugin URI: http://reservationdiary.eu/eng/reservation-wordpress-plugin/
  Description: ReDi Reservation plugin for Restaurants
  Version: 14.0221
  Author: reservationdiary.eu
  Author URI: http://reservationdiary.eu/
  Text Domain: redi-restaurant-reservation
  Domain Path: /lang

 */
if (!defined('REDI_RESTAURANT_PLUGIN_URL'))
	define('REDI_RESTAURANT_PLUGIN_URL', plugin_dir_url(__FILE__));
if (!defined('REDI_RESTAURANT_TEMPLATE'))
	define('REDI_RESTAURANT_TEMPLATE', plugin_dir_path(__FILE__).'templates'.DIRECTORY_SEPARATOR);
if (!defined('REDI_RESTAURANT_DEBUG'))
	define('REDI_RESTAURANT_DEBUG', FALSE);
if (!defined('ID'))
	define('ID', 'ID');
require_once('redi.php');


if (!class_exists('ReDiRestaurantReservation'))
{
	class ReDiRestaurantReservation
	{
		public $version = '14.0221';
		/**
		 * @var string The options string name for this plugin
		 */
		private $optionsName = 'wp_redi_restaurant_options';
		private static $name = 'REDI_RESTAURANT';
		private $options = array ();
		private $ApiKey;
		private $redi;
		private $weekday = array ('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

		function ReDiRestaurantReservation()
		{
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

				$this->options[REDI_APIKEY] = $new_account[REDI_APIKEY];
				$this->redi->setApiKey($this->options[REDI_APIKEY]);
				$place = $this->redi->createPlace(array (
				                                        'place' => array (
					                                        'Name' => 'Name[change it in admin]',
					                                        'City' => 'city',
					                                        'Country' => 'country',
					                                        'Address' => 'Address line 1',
					                                        'Email' => get_option('admin_email'),
					                                        'Phone' => '[areacode] [number]',
					                                        'WebAddress' => get_option('siteurl'),
					                                        'Lang' => str_replace('_', '-', get_locale()),
					                                        'MinTimeBeforeReservation' => 24, // hour
					                                        'DescriptionShort' => get_option('blogdescription'),
					                                        'DescriptionFull' => '',
					                                        'Catalog' => true
				                                        )
				                                  ));


				//$this->options['placeID'] = 
                $placeID = (int)$place[ID];

				$category = $this->redi->createCategory($placeID,
					array ('category' => array ('Name' => 'Restaurant')));

				//$this->options['categoryID'] = 
                $categoryID = (int)$category[ID];
				$service = $this->redi->createService($categoryID,
					array ('service' => array ('Name' => 'Person', 'Quantity' => 10)));

				foreach ($this->weekday as $value)
					$times[$value] = array ('OpenTime' => '12:00', 'CloseTime' => '00:00');
				$this->redi->setServiceTime($categoryID, $times);

				$this->options['serviceID'] = $serviceID = (int)$service[ID];
				$this->saveAdminOptions();
			}
		}

		public function __construct()
		{
			$this->_name = self::$name;
			//Initialize the options
			$this->get_options();

			$this->ApiKey = isset($this->options[REDI_APIKEY]) ? $this->options[REDI_APIKEY] : NULL;
			$this->redi = new Redi($this->ApiKey);
			//Actions
			add_action('init', array (&$this, 'init_sessions'));
			add_action('admin_menu', array (&$this, 'redi_restaurant_admin_menu_link'));

			$this->page_title = 'Reservation';
			$this->content = '[redirestaurant]';
			$this->page_name = $this->_name;
			$this->page_id = '0';

			register_activation_hook(__FILE__, array ($this, 'activate'));
			register_deactivation_hook(__FILE__, array ($this, 'deactivate'));
			register_uninstall_hook(__FILE__, array ('ReDiRestaurantReservation', 'uninstall'));

			add_action('wp_ajax_nopriv_redi_restaurant-submit', array (&$this, 'redi_restaurant_ajax'));
			add_action('wp_ajax_redi_restaurant-submit', array (&$this, 'redi_restaurant_ajax'));
			add_shortcode('redirestaurant', array ($this, 'shortcode'));

		}

		/**
		 * Saves the admin options to the database.
		 */
		function saveAdminOptions()
		{
			return update_option($this->optionsName, $this->options);
		}


		/**
		 * Adds settings/options page
		 */
		function redi_restaurant_admin_options_page()
		{
			$errors = array();
			if ($this->ApiKey == NULL) /// TODO: move to install
			{
			        $this->register();
			}

			$places = $this->redi->getPlaces();


			$placeID = $places[0]->ID;

			$categories = $this->redi->getPlaceCategories($placeID);

			$serviceID = $this->options['serviceID'];

			$categoryID =  $categories[0]->ID;

			if(isset($_POST['action']) && $_POST['action']=='cancel')
			{
                
				if(isset($_POST['id']) && ((int)$_POST['id']) > 0)
				{
					$ret = $this->redi->cancelReservation($_POST['id'], str_replace('_', '-', get_locale()), $_POST['reason']);

					if(isset($ret['Error']))
					{
						$errors[] = $ret['Error'];
					}
				}
				else
				{
					$errors[] = __('id and reason are required', 'redi-restaurant-reservation');
				}
			}

			if (isset($_POST['submit']))
			{
                $settings_saved = true;

				$minPersons = (int)$_POST['MinPersons'];
				$maxPersons = (int)$_POST['MaxPersons'];
				if( $minPersons >= $maxPersons)
				{
					$errors[] = //new WP_Error('required',
						__('Min Persons should be lower than Max Persons');
				}
				$this->options['MinPersons'] = $minPersons;
				$this->options['MaxPersons'] = $maxPersons;
				
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

				foreach ($_POST['OpenTime'] as $key => $value)
                {
					if (self::set_and_not_empty($value))
                    {
						$times[$key]['OpenTime'] = $value;
                    }
                }
				foreach ($_POST['CloseTime'] as $key => $value)
                {
					if (self::set_and_not_empty($value))
                    {
						$times[$key]['CloseTime'] = $value;
                    }
                }

				$services = (int)$_POST['services'];

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

						$ret = $this->redi->deleteServices($ids);
						if(isset($ret['Error']))
						{
						    $errors[] = $ret['Error'];
						    $settings_saved = false;
						}
						$ret = array();
					}
					else
					{
						//add
						$diff = $services - count($getServices);

						$ret = $this->redi->createService($categoryID,
						        array (
						              'service' => array (
						                      'Name' => 'Person',
						                      'Quantity' => $diff
						              )
						        ));
						if(isset($ret['Error']))
						{
						    $errors[] = $ret['Error'];
						    $settings_saved = false;
						}
						$ret = array();
					}
				}
				$this->options['Thanks'] = isset($_POST['Thanks']) ? (int)$_POST['Thanks'] : 0;
				$this->options['TimePicker'] = isset($_POST['TimePicker']) ? $_POST['TimePicker'] : null;
				$this->options['services'] = $services;
                                $this->options['MinTimeBeforeReservation'] = $_POST['MinTimeBeforeReservation'];
				$this->options['DateFormat'] = $_POST['DateFormat'];
				$this->options['ReservationTime'] = $_POST['ReservationTime'];

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

                $this->saveAdminOptions();

				if (is_array($times) && count($times))
                {
					$ret = $this->redi->setServiceTime($categoryID, $times);
                    if(isset($ret['Error']))
                    {
                        $errors[] = $ret['Error'];
                        $settings_saved = false;
                    }
                    $ret = array();
                    
                }
				$ret = $this->redi->setPlace($placeID,
					array (
						'place' => array (
							'Name' => $_POST['Name'],
							'City' => $_POST['City'],
							'Country' => $_POST['Country'],
							'Address' => $_POST['Address'],
							'Email' => $_POST['Email'],
							'Phone' => $_POST['Phone'],
							'WebAddress' => $_POST['WebAddress'],
							'Lang' => $_POST['Lang'],
							'DescriptionShort' => $_POST['DescriptionShort'],
							'DescriptionFull' => $_POST['DescriptionFull'],
							'MinTimeBeforeReservation' => $_POST['MinTimeBeforeReservation'],
							'Catalog' => (int)$_POST['Catalog'],
							'DateFormat' =>$_POST['DateFormat']
							)
						)
				);
                if(isset($ret['Error']))
                {
                    $errors[] = $ret['Error'];
                    $settings_saved = false;
                }
				
                $places = $this->redi->getPlaces();    
                if(isset($places['Error']))
                {
                    $errors[] = $places['Error'];
                }
			}
            
			$getServices = $this->redi->getServices($categoryID);
			if(isset($getServices['Error']))
			{
				$errors[] = $getServices['Error'];
			}

			$options = get_option($this->optionsName);

			$thanks = isset($options['Thanks']) ? $options['Thanks'] : 0;
			$timepicker = isset($options['TimePicker']) ? $options['TimePicker'] : null;
			$minPersons = isset($options['MinPersons']) ? $options['MinPersons']: 1;
			$maxPersons = isset($options['MaxPersons']) ? $options['MaxPersons']: 10;

			for($i = 1; $i != CUSTOM_FIELDS; $i++)
			{
				$field_name = 'field_'.$i.'_name';
				$field_type = 'field_'.$i.'_type';
				$field_required = 'field_'.$i.'_required';
				$field_message = 'field_'.$i.'_message';

				if(isset($options[$field_name]))
				{
					$$field_name = $options[$field_name];
				}

				if(isset($options[$field_type]))
				{
					$$field_type = $options[$field_type];
				}

				if(isset($options[$field_required]))
				{
					$$field_required = $options[$field_required];
				}

				if(isset($options[$field_message]))
				{
					$$field_message = $options[$field_message];
				}
			}
            $ReservationTime = $this->getReservationTime();

            require_once(REDI_RESTAURANT_TEMPLATE.'admin.php');
            require_once(REDI_RESTAURANT_TEMPLATE.'basicpackage.php');
        }
        
        function ajaxed_admin_page($placeID, $categoryID)
        {
            require_once(plugin_dir_path(__FILE__).'languages.php');
            $places = $this->redi->getPlaces();
            $serviceTimes = $this->redi->getServiceTime($categoryID); //goes to template 'admin'
            $place = $this->redi->getPlace($placeID); //goes to template 'admin'
            
            $getServices = $this->redi->getServices($categoryID);

            require_once(REDI_RESTAURANT_TEMPLATE.'admin_ajaxed.php');
	}

		function init_sessions()
		{
			if (!session_id())
				session_start();

			if (function_exists('load_plugin_textdomain'))
			{
				load_plugin_textdomain( 'redi-restaurant-reservation', false, 'redi-restaurant-reservation/lang');
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
				$_p['post_category'] = array (1); // the default 'Uncatrgorised'
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

		public function shortcode()
		{
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
                                  'name_missing'  => __('Name can\'t be empty', 'redi-restaurant-reservation'),
                                  'email_missing' => __('Email can\'t be empty', 'redi-restaurant-reservation'),
                                  'phone_missing' => __('Phone can\'t be empty', 'redi-restaurant-reservation'),
                            ));
                    wp_enqueue_script('restaurant');

                    wp_register_style('redi-restaurant',
                            REDI_RESTAURANT_PLUGIN_URL.'/css/restaurant.css');
                    wp_enqueue_style('redi-restaurant');
                  //  $persons = 2; //min can be bigger than 2

                    //places 
                    $places = $this->redi->getPlaces();

                    $placeID = $places[0]->ID;


                    $time_format = get_option('time_format');
                    $date_format_setting = $this->options['DateFormat'];

                    $time_format = get_option('time_format');
					$date_format_setting = $this->options['DateFormat'];

					$calendar_date_format = $this->getCalendarDateFormat($date_format_setting);
					$date_format = $this->getPHPDateFormat($date_format_setting);

                    $MinTimeBeforeReservation = (int)($this->options['MinTimeBeforeReservation'] > 0 ? $this->options['MinTimeBeforeReservation'] : 0) + 1;

					$reservationStartTime = strtotime('+'.$MinTimeBeforeReservation.' hour', current_time('timestamp'));
                    $startDate = date($date_format, $reservationStartTime);
                    $startDateISO = date('Y-m-d', $reservationStartTime);
					$startTime = mktime(date("G", $reservationStartTime), 0, 0, 0, 0, 0);

					$minPersons = isset($this->options['MinPersons']) ? $this->options['MinPersons'] : 1;
					$maxPersons = isset($this->options['MaxPersons']) ? $this->options['MaxPersons'] : 10;
                    $thanks = $this->options['Thanks'];

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

					$time_format_hours = str_replace(':i', '', get_option('time_format'));

					$timepicker = isset($this->options['TimePicker']) ? $this->options['TimePicker'] : null;
                    require_once(REDI_RESTAURANT_TEMPLATE.'frontend.php');
                    $out = ob_get_contents();

                    ob_end_clean();
                    return $out;
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
                    //$placeID = (int)$_POST['placeID'];
                    // convert date to array
                    $date = date_parse($_POST['startDateISO'].' '.$_POST['startTime']);

                    if ($date['error_count'] > 0)
                    {
                        echo json_encode(array('Error' => __('Selected date or time is not valid.', 'redi-restaurant-reservation')));
                        die;
                    }

                    $startTimeStr = $date['year'].'-'.$date['month'].'-'.$date['day'].' '.$date['hour'].':'.$date['minute'];

                    // convert to int
                    $startTimeInt = strtotime($startTimeStr, 0);

                    // calculate end time
                    $endTimeInt = strtotime('+'.$this->getReservationTime().'minutes', $startTimeInt);

                    // format to ISO
                    $startTimeISO   = date('Y-m-d H:i', $startTimeInt);
                    $endTimeISO     = date('Y-m-d H:i', $endTimeInt);
                    $currentTimeISO = date('Y-m-d H:i', current_time('timestamp'));

                    $params = array(
                        'StartTime'    => urlencode($startTimeISO),
                        'EndTime'      => urlencode($endTimeISO),
                        'Quantity'     => (int) $_POST['persons'],
                        'Alternatives' => 2,
                        'Lang'         => str_replace('_', '-', get_locale()),
                        'CurrentTime'  => urlencode($currentTimeISO)
                    );
                    //get first category on selected place

                    $categories = $this->redi->getPlaceCategories($placeID);
                    if(isset($categories['Error']))
                    {
                        echo json_encode($categories);
                        die;
                    }
                    $category   = $categories[0];

                    $query = $this->redi->query($category->ID, $params);

                    $time_format = get_option('time_format');

                    if (!isset($query['Error']))
                    {
                        unset($query['debug']);
                        foreach ($query as $q)
                        {
                            $q->Select       = ($startTimeISO == $q->StartTime && $q->Available);
                            $q->StartTimeISO = $q->StartTime;
                            $q->StartTime    = date($time_format, strtotime($q->StartTime));
                            $q->EndTime      = date($time_format, strtotime($q->EndTime));
                        }
                    }
                    echo json_encode($query);
                    break;

                case 'step3':

                    $startTimeStr = $_POST['startTime'];

                    // convert to int
                    $startTimeInt = strtotime($startTimeStr, 0);

                    // calculate end time
                    $endTimeInt = strtotime('+'.$this->getReservationTime().'minutes', $startTimeInt);

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
                    $comment .= $_POST['UserComments'];


                    $params = array(
                        'reservation' => array(
                            'StartTime'    => $startTimeISO,
                            'EndTime'      => $endTimeISO,
                            'Quantity'     => (int) $_POST['persons'],
                            "UserName"     => $_POST['UserName'],
                            "UserEmail"    => $_POST['UserEmail'],
                            "UserComments" => $comment,
                            "UserPhone"    => $_POST['UserPhone'],
                            "Name"         => "Person",
                            "Lang"         => str_replace('_', '-', get_locale()),
                            'CurrentTime'  => $currentTimeISO
                        )
                    );

                    $reservation = $this->redi->createReservation(
                            $categoryID
                            //$this->options['categoryID']
                            , $params);
                    echo json_encode($reservation);
                    break;

                case 'get_place':
                    self::ajaxed_admin_page($placeID, $categoryID);

                    break;
            }

            die;
        }

        private function getReservationTime()
        {
                if (isset($this->options['ReservationTime']) && $this->options['ReservationTime']>0)
                {
                        return (int) $this->options['ReservationTime'];
                }
                return 3*60;
        }
    }
}
new ReDiRestaurantReservation();

register_activation_hook(__FILE__, array ('ReDiRestaurantReservation', 'install'));