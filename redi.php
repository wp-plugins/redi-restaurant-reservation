<?php
/**
 * @author: roboter
 * @date: 22.10.12
 * @time: 20:03
 *
 */

if ( ! defined( 'REDI_RESTAURANT_DEBUG' ) ) {
	define( 'REDI_RESTAURANT_DEBUG', false );
}
if ( ! defined( 'USER' ) ) {
	define( 'USER', 'User.svc/' );
}
if ( ! defined( 'USERGET' ) ) {
	define( 'USERGET', 'User.svc/get' );
}
if ( ! defined( 'PLACE' ) ) { // 'Place.svc/place' - for old version
	define( 'PLACE', 'Place.svc/' );
}
if ( ! defined( 'SERVICE' ) ) {
	define( 'SERVICE', 'Service.svc/' );
}
if ( ! defined( 'CATEGORY' ) ) {
	define( 'CATEGORY', 'Category.svc/' );
}
if ( ! defined( 'RESERVATION' ) ) {
	define ( 'RESERVATION', 'Reservation.svc/' );
}
if ( ! defined( 'EMAILCONTENT' ) ) {
	define( 'EMAILCONTENT', 'emailcontent.svc/' );
}
if ( ! defined( 'DATES' ) ) {
	define( 'DATES', 'Date.svc/' );
}
if ( ! defined( 'POST' ) ) {
	define( 'POST', 'POST' );
}
if ( ! defined( 'GET' ) ) {
	define( 'GET', 'GET' );
}
if ( ! defined( 'PUT' ) ) {
	define( 'PUT', 'PUT' );
}
if ( ! defined( 'DELETE' ) ) {
	define( 'DELETE', 'DELETE' );
}
if ( ! defined( 'REDI_SUCCESS' ) ) {
	define( 'REDI_SUCCESS', 'SUCCESS' );
}
if ( ! defined( 'REDI_RESTAURANT_API' ) ) {
	define( 'REDI_RESTAURANT_API', 'http://api.reservationdiary.eu/service/' );
}

if ( ! defined( 'CUSTOM_FIELDS' ) ) {
	define( 'CUSTOM_FIELDS', 6 );
}

if ( ! class_exists( 'ReDi' ) ) {
	class Redi {
		private $ApiKey;

		public function Redi( $ApiKey ) {
			$this->ApiKey = $ApiKey;
		}

		public function getBlockingDates( $lang, $categoryID, $params ) {
			//Date.svc/en/[APIKEY]/[CategoryID]?StartTime=2015-03-01&EndTime=2015-03-31
			return $this->request( REDI_RESTAURANT_API . DATES  . $lang . '/'.$this->ApiKey.'/'. $categoryID, GET,  $this->strParams( $params ) );
		}


		public function getReservationUrl( $lang ) {
			return 'http://wp.reservationdiary.eu/' . $lang . '/' . $this->ApiKey . '/Reservation/Index';
		}

		public function getEmailContent( $reservationID, $type, $params ) {
			return $this->request( REDI_RESTAURANT_API . EMAILCONTENT . $this->ApiKey . '/' . $reservationID . '/ClientReservation' . $type,
				GET, $this->strParams( $params ) );
		}

		public function cancelReservationByClient( $params ) {
			return $this->request( REDI_RESTAURANT_API . RESERVATION . $this->ApiKey . '/cancelByClient', DELETE,
				$this->strParams( $params ) );
		}

		public function cancelReservation( $params ) {
			return $this->request( REDI_RESTAURANT_API . RESERVATION . $this->ApiKey . '/cancelByProvider', DELETE,
				$this->strParams( $params ) );
		}

		public function createReservation( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . RESERVATION . $this->ApiKey . '/' . $categoryID, POST,
				json_encode( self::unescape_array( $params ) ) );
		}

		/**
		 * @param $categoryID
		 * @param $params array
		 * <pre>
		 * StartTime -
		 * EndTime
		 * Quantity
		 * Alternatives
		 * </pre>
		 *
		 * @return array
		 */
		public function query( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . RESERVATION . $this->ApiKey . '/' . $categoryID . '/Person',
				GET, $this->strParams( $params ) );
		}

		public function createCategory( $placeID, $params ) {
			return $this->request( REDI_RESTAURANT_API . CATEGORY . $this->ApiKey . '/' . $placeID, POST,
				json_encode( self::unescape_array( $params ) ) );
		}

		public function getServices( $categoryID ) {
			return $this->request( REDI_RESTAURANT_API . SERVICE . $this->ApiKey . '/' . $categoryID . '/Person', GET );
		}

		public function deleteServices( $ids ) {
			return $this->request( REDI_RESTAURANT_API . SERVICE . $this->ApiKey . '?serviceID=' . join( ',', $ids ),
				DELETE );
		}

		public function setServiceTime( $categoryID, $timeSet ) {
			return $this->request( REDI_RESTAURANT_API . CATEGORY . $this->ApiKey . '/' . $categoryID . '/time',
				PUT,
				json_encode( self::unescape_array( array( 'timeSet' => $timeSet ) ) ) );
		}

		public function getServiceTime( $categoryID ) {
			return $this->request( REDI_RESTAURANT_API . CATEGORY . $this->ApiKey . '/' . $categoryID . '/time', GET );
		}

		public function createService( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . SERVICE . $this->ApiKey . '/' . $categoryID, POST,
				json_encode( self::unescape_array( $params ) ) );
		}

		public function userGetError() {
			return $this->request( REDI_RESTAURANT_API . USERGET );
		}

		public function createUser( $params ) {
			return $this->request( REDI_RESTAURANT_API . USER, POST, json_encode( self::unescape_array( $params ) ) );
		}

		public function setPlace( $placeID, $params ) {
			return $this->request( REDI_RESTAURANT_API . PLACE . $this->ApiKey . '/' . $placeID, PUT,
				json_encode( self::unescape_array( $params ) ) );
		}

		public function createPlace( $params ) {
			return $this->request( REDI_RESTAURANT_API . PLACE . $this->ApiKey, POST,
				json_encode( self::unescape_array( $params ) ) );
		}

		public function shiftsStartTime( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . CATEGORY . $this->ApiKey . '/' . $categoryID . '/shiftsStartTime',
				GET, $this->strParams( $params ) );
		}

		public function availabilityByDay( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . RESERVATION . $this->ApiKey . '/' . $categoryID . '/availabilityByDay/Person',
				GET, $this->strParams( $params ) );
		}

		public function availabilityByShifts( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . RESERVATION . $this->ApiKey . '/' . $categoryID . '/availabilityByShifts/Person',
				GET, $this->strParams( $params ) );
		}

		public function getPlace( $placeID ) {
			return $this->request( REDI_RESTAURANT_API . PLACE . $this->ApiKey . '/' . $placeID, GET );
		}

		public function getPlaceCategories( $placeID ) {
			return $this->request( REDI_RESTAURANT_API . PLACE . $this->ApiKey . '/' . $placeID . '/categories', GET );
		}

		public function getPlaces() {
			return $this->request( REDI_RESTAURANT_API . PLACE . $this->ApiKey, GET );
		}

		public function setApiKey( $ApiKey ) {
			$this->ApiKey = $ApiKey;
		}

		public function strParams( $params ) {
			$url_param = '';
			$first     = 0;

			if ( is_array( $params ) ) {
				foreach ( $params as $param_name => $param_value ) {
					$url_param .= ( ( $first ++ == 0 ) ? '?' : '&' ) . $param_name . '=' . $param_value;
				}
			}

			return $url_param;
		}

		private static function unescape_array( $array ) {
			$unescaped_array = array();
			foreach ( $array as $key => $val ) {
				if ( is_array( $val ) ) {
					$unescaped_array[ $key ] = self::unescape_array( $val );
				} else {
					$unescaped_array[ $key ] = stripslashes( $val );
				}
			}

			return $unescaped_array;
		}

		private function request( $url, $method = GET, $params_string = null ) {
			$request = new WP_Http;
			$curTime = microtime( true );
			$req     = array(
				'method'  => $method,
				'body'    => $params_string,
				'headers' => array(
					'Content-Type'   => 'application/json; charset=utf-8',
					'Content-Length' => $method == GET ? 0 : strlen( $params_string )
				)
			);
			$output  = $request->request(
				$url . ( ( $method === GET || $method === DELETE ) ? $params_string : '' ), $req );
			if ( is_wp_error( $output ) ) {
				return array(
					'request_time' => round( microtime( true ) - $curTime, 3 ) * 1000,
					'Error'        => __( 'Online reservation service is not available at this time. Try again later or contact us directly.',
						'redi-restaurant-reservation' ),
					'Wp-Error'     => $output->errors
				);
			}

			if ( $output['response']['code'] != 200 && $output['response']['code'] != 400 ) {
				return array(
//                    'get' => $url . ( ( $method === GET || $method === DELETE ) ? $params_string : '' ),
//                    'debug' => var_export($req, true),
					'response_code' => $output['response']['code'],
					'Error'         => __( 'Online reservation service is not available at this time. Try again later or contact us directly.',
						'redi-restaurant-reservation' )
				);
			}
			$output = $output['body'];

			// convert response
			$output = (array) json_decode( $output );
			if ( REDI_RESTAURANT_DEBUG ) {
				$output['debug'] = array
				(
					'method' => $method,
					'params' => self::p( $params_string, true ),
					'url'    => self::p( $url . ( ( $method == GET || $method == DELETE ) ? $params_string : '' ),
						true ),
				);
			}

			return $output;
		}

		public static function d( $object, $color = true ) {
			if ( REDI_RESTAURANT_DEBUG ) {
				if ( ! $color ) {
					echo '<pre>';
					var_dump( $object );
					echo '<pre>';

					return;
				}
				$result = highlight_string( "<?php\n" . print_r( $object, true ), true );
				echo '<pre style="text-align: left;">' . preg_replace( '/&lt;\\?php<br \\/>/', '', $result,
						1 ) . '</pre><br />';
			}

		}

		public static function p( $object ) {
			if ( REDI_RESTAURANT_DEBUG ) {
				return var_export( $object, true );
			}
		}
	}
}
