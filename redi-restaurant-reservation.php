<?php
/*
  Plugin Name: ReDi Restaurant Reservation
  Plugin URI: http://reservationdiary.eu/eng/reservation-wordpress-plugin/
  Description: ReDi Reservation plugin for Restaurants
  Version: 13.0114
  Author: reservationdiary.eu
  Author URI: http://reservationdiary.eu/
 */
if (!defined('REDI_RESTAURANT_PLUGIN_URL'))
	define('REDI_RESTAURANT_PLUGIN_URL', plugin_dir_url(__FILE__));
if (!defined('REDI_TEMPLATE'))
	define('REDI_TEMPLATE', plugin_dir_path(__FILE__).'templates'.DIRECTORY_SEPARATOR);
if (!defined('REDI_RESTAURANT_DEBUG'))
	define('REDI_RESTAURANT_DEBUG', TRUE);
if (!defined('ID'))
	define('ID', 'ID');
require_once('redi.php');


if (!class_exists('ReDiRestaurantReservation'))
{

	class ReDiRestaurantReservation
	{

		public $version = '13.0114';

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
			Redi::p($new_account);
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
					                                        'WebAddress' => get_option('siteurl')
				                                        )
				                                  ));

				$this->options['placeID'] = $placeID = (int)$place[ID];

				$category = $this->redi->createCategory($placeID,
					array ('category' => array ('Name' => 'Restaurant')));

				$this->options['categoryID'] = $categoryID = (int)$category[ID];
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
			$this->content = '[redirestaraunt]';
			$this->page_name = $this->_name;
			$this->page_id = '0';

			register_activation_hook(__FILE__, array ($this, 'activate'));
			register_deactivation_hook(__FILE__, array ($this, 'deactivate'));
			register_uninstall_hook(__FILE__, array ('ReDiRestaurantReservation', 'uninstall'));


			add_action('wp_ajax_nopriv_redi_restaurant-submit', array (&$this, 'redi_restaurant_ajax'));
			add_action('wp_ajax_redi_restaurant-submit', array (&$this, 'redi_restaurant_ajax'));
			add_shortcode('template', array ($this, 'shortcode'));
			register_activation_hook(__FILE__, array ($this, 'activate'));

			add_shortcode('redirestaraunt', array ($this, 'shortcode'));
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
			if ($this->ApiKey == NULL) /// TODO: move to install
				$this->register();

			//redi::p($this->ApiKey);
			$placeID = $this->options['placeID'];
			$serviceID = $this->options['serviceID'];
			$categoryID = $this->options['categoryID'];
			//Redi::p($placeID);
			if (isset($_POST['submit']))
			{
				$this->options['OpenTime'] = $_POST['OpenTime'];
				$this->options['CloseTime'] = $_POST['CloseTime'];

				foreach ($_POST['OpenTime'] as $key => $value)
					if (self::set_and_not_empty($value))
						$times[$key]['OpenTime'] = $value;
				foreach ($_POST['CloseTime'] as $key => $value)
					if (self::set_and_not_empty($value))
						$times[$key]['CloseTime'] = $value;

				$services = (int)$_POST['services'];
				$getServices = $this->redi->getServices($categoryID);

				if (count($getServices) != $services)
				{
					if (count($getServices) > $services)
					{
						//delete
						$diff = count($getServices) - $services;
						$ids = array ();

						$removeServices = array_slice($getServices, 0, $diff);
						foreach ($removeServices AS $service)
						{
							//Redi::p($service);
							$ids[] = $service->ID;
						}

						$this->redi->deleteServices($categoryID, $ids);
					}
					else
					{
						//add
						$diff = $services - count($getServices);

						$this->redi->createService($categoryID,
							array (
							      'service' => array (
								      'Name' => 'Person',
								      'Quantity' => $diff
							      )
							));
					}
				}

				$this->saveAdminOptions();

				if (is_array($times) && count($times))
					$this->redi->setServiceTime($categoryID, $times);

				$place = $this->redi->setPlace($placeID,
					array (
					      'place' => array (
						      'Name' => $_POST['Name'],
						      'City' => $_POST['City'],
						      'Country' => $_POST['Country'],
						      'Address' => $_POST['Address'],
						      'Email' => $_POST['Email'],
						      'Phone' => $_POST['Phone'],
						      'WebAddress' => $_POST['WebAddress']
					      )
					));


			}
			$place = $this->redi->getPlace($placeID); //goes to template 'admin'
			$serviceTimes = $this->redi->getServiceTime($categoryID); //goes to template 'admin'

			$getServices = $this->redi->getServices($categoryID);
			//			echo '<pre>';
			//			var_dump($getServices);
			$options = get_option($this->optionsName);
			require_once(REDI_TEMPLATE.'admin.php');
		}

		function init_sessions()
		{
			if (!session_id())
				session_start();
		}

		/**
		 * @desc Adds the options subpanel
		 */
		function redi_restaurant_admin_menu_link()
		{
			add_options_page('Redi Restaurant Reservation',
				'Redi Restaurant Reservation',
				10,
				basename(__FILE__),
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

		public function shortcode()
		{
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
			wp_register_script('restaurant', REDI_RESTAURANT_PLUGIN_URL.'js/restaurant.js', array ('jquery'));
			wp_localize_script('restaurant',
				'AjaxUrl',
				array ( // URL to wp-admin/admin-ajax.php to process the request
				      'ajaxurl' => admin_url('admin-ajax.php')
				));
			wp_enqueue_script('restaurant');
			$persons = 2;
			$startDate = date('Y-m-d', strtotime('+27 hour'));
			$startTime = date('G:00', strtotime('+27 hour'));
			require_once(REDI_TEMPLATE.'frontend.php');
		}

		function redi_restaurant_ajax()
		{
			switch ($_POST['get'])
			{
				case 'step1':
					$params = array (
						'StartTime' => urlencode(date('Y-m-d H:i',
							strtotime($_POST['startDate'].' '.$_POST['startTime']))),
						'EndTime' => urlencode(date('Y-m-d H:i',
							strtotime($_POST['startDate'].' '.$_POST['startTime'].' +3 hour'))),
						'Quantity' => (int)$_POST['persons'],
						'Alternatives' => 2
					);
					$query = $this->redi->query($this->options['categoryID'], $params);
					//Redi::p($query);
					if (!isset($query['Error']))
					{
						unset($query['debug']);
						foreach ($query as $q)
						{
							$q->StartTime = date('H:i', strtotime($q->StartTime));
							$q->EndTime = date('H:i', strtotime($q->EndTime));
						}
					}
					echo json_encode($query);
					break;
				case 'step3':

					$params = array (
						'reservation' => array (

							'StartTime' => (date('Y-m-d H:i',
								strtotime($_POST['startDate'].' '.$_POST['startTime']))),
							'EndTime' => (date('Y-m-d H:i',
								strtotime($_POST['startDate'].' '.$_POST['startTime'].' +3 hour'))),
							'Quantity' => (int)$_POST['persons'],
							"UserName" => $_POST['UserName'],
							"UserEmail" => $_POST['UserEmail'],
							"UserComments" => $_POST['UserComments'],
							"UserPhone" => $_POST['UserPhone'],
							"Name" => "Person"
						)
					);
				//	var_dump($params);
					$reservation = $this->redi->reservation($this->options['categoryID'], $params);
					echo json_encode($reservation);
					break;
				default:
					//var_dump($_POST);
			}
			die;
		}
	}
}
new ReDiRestaurantReservation();

register_activation_hook(__FILE__, array ('ReDiRestaurantReservation', 'install'));