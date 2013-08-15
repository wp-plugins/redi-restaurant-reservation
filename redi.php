<?php
/**
 * @author: roboter
 * @date: 22.10.12
 * @time: 20:03
 *
 */
if (!defined('REDI_RESTAURANT_DEBUG'))
	define('REDI_RESTAURANT_DEBUG', FALSE);

if (!defined('USER'))
	define('USER', 'User.svc/');

if (!defined('USERGET'))
	define('USERGET', 'User.svc/get');

if (!defined('PLACE')) // 'Place.svc/place' - for old version
	define('PLACE', 'Place.svc/');

if (!defined('SERVICE'))
	define('SERVICE', 'Service.svc/');

if (!defined('CATEGORY'))
	define('CATEGORY', 'Category.svc/');

if (!defined('RESERVATION'))
	define ('RESERVATION', 'Reservation.svc/');

if (!defined('POST'))
	define('POST', 'POST');

if (!defined('GET'))
	define('GET', 'GET');

if (!defined('PUT'))
	define('PUT', 'PUT');

if (!defined('DELETE'))
	define('DELETE', 'DELETE');

if (!defined('REDI_SUCCESS'))
	define('REDI_SUCCESS', 'SUCCESS');

if (!defined('REDI_APIKEY'))
	define('REDI_APIKEY', 'ID');

if (!defined('REDI_RESTAURANT_API'))
{
	define('REDI_RESTAURANT_API', 'http://api.reservationdiary.eu/service/');
}

class Redi
{
	private $ApiKey;

	public function Redi($ApiKey)
	{
		$this->ApiKey = $ApiKey;
	}

	public function cancelReservation($id, $lang, $reason)
	{
		return $this->curl(REDI_RESTAURANT_API.RESERVATION.$this->ApiKey.'/cancelByProvider?id='.$id.'&Lang='.$lang.'&reason='.urlencode($reason), DELETE);
	}

	public function createReservation($categoryID, $params)
	{
		return $this->curl(REDI_RESTAURANT_API.RESERVATION.$this->ApiKey.'/'.$categoryID, POST, json_encode(self::unescape_array($params)));
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
	 * @return array
	 */
	public function query($categoryID, $params)
	{
		return $this->curl(REDI_RESTAURANT_API.RESERVATION.$this->ApiKey.'/'.$categoryID.'/Person', GET, $this->strParams($params));
	}

	public function createCategory($placeID, $params)
	{
		return $this->curl(REDI_RESTAURANT_API.CATEGORY.$this->ApiKey.'/'.$placeID, POST, json_encode(self::unescape_array($params)));
	}

	public function getServices($categoryID)
	{
		return $this->curl(REDI_RESTAURANT_API.SERVICE.$this->ApiKey.'/'.$categoryID.'/Person', GET);
	}

	public function deleteServices($ids)
	{
		return $this->curl(REDI_RESTAURANT_API.SERVICE.$this->ApiKey.'?serviceID='.join(',', $ids), DELETE);
	}

	public function setServiceTime($categoryID, $timeSet)
	{
		return $this->curl(REDI_RESTAURANT_API.CATEGORY.$this->ApiKey.'/'.$categoryID.'/time',
			PUT,
			json_encode(self::unescape_array(array ('timeSet' => $timeSet))));
	}

	public function getServiceTime($categoryID)
	{
		return $this->curl(REDI_RESTAURANT_API.CATEGORY.$this->ApiKey.'/'.$categoryID.'/time', GET);
	}

	public function createService($categoryID, $params)
	{
		return $this->curl(REDI_RESTAURANT_API.SERVICE.$this->ApiKey.'/'.$categoryID, POST, json_encode(self::unescape_array($params)));
	}

	public function userGetError()
	{
		return $this->curl(REDI_RESTAURANT_API.USERGET);
	}

	public function createUser($params)
	{
		return $this->curl(REDI_RESTAURANT_API.USER, POST, json_encode(self::unescape_array($params)));
	}

	public function setPlace($placeID, $params)
	{
		return $this->curl(REDI_RESTAURANT_API.PLACE.$this->ApiKey.'/'.$placeID, PUT, json_encode(self::unescape_array($params)));
	}

	public function createPlace($params)
	{
		return $this->curl(REDI_RESTAURANT_API.PLACE.$this->ApiKey, POST, json_encode(self::unescape_array($params)));
	}

	public function getPlace($placeID)
	{
		return $this->curl(REDI_RESTAURANT_API.PLACE.$this->ApiKey.'/'.$placeID, GET);
	}

	public function setApiKey($ApiKey)
	{
		$this->ApiKey = $ApiKey;
	}

	public function strParams($params)
	{
		$url_param = '';
		$first = 0;

		if (is_array($params))
			foreach ($params as $param_name => $param_value)
				$url_param .= (($first++ == 0) ? '?' : '&').$param_name.'='.$param_value;


		return $url_param;
	}

	private static function unescape_array($array)
	{
		$unescaped_array = array();
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$unescaped_array[$key] = self::unescape_array($val);
			}
			else
			{
				$unescaped_array[$key] = stripslashes($val);
			}
		}
		return $unescaped_array;
	}

	private function curl($url, $method = GET, $params_string = NULL)
	{
		$ch = curl_init();

		$params = self::p($params_string, TRUE);

		$debug_url = self::p($url.(($method == GET || $method == DELETE) ? $params_string : ''), TRUE);
		curl_setopt($ch, CURLOPT_URL, $url.(($method == GET || $method == DELETE) ? $params_string : ''));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//		self::p($method.':'.$url.':'.$params_string);

		switch ($method)
		{
			case GET:
				curl_setopt($ch, CURLOPT_HEADER, false);
				break;

			case POST:
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
				curl_setopt($ch,
					CURLOPT_HTTPHEADER,
					array ('Content-Type: application/json; charset=utf-8'));
				break;

			case PUT:
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
				curl_setopt($ch,
					CURLOPT_HTTPHEADER,
					array ('Content-Type: application/json; charset=utf-8'));
				break;

			case DELETE:
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}

		$output = curl_exec($ch);

		if ($output === FALSE)
		{
			echo 'Curl error: '.curl_error($ch);
		}
		curl_close($ch);

		// convert response
		$output = (array)json_decode($output);
		if (REDI_RESTAURANT_DEBUG)
		{
			$output['debug'] = array
			(
				'method' => $method,
				'params' => $params,
				'url' => $debug_url,
			);
		}

		return $output;
	}

	public static function d($object, $color = true)
	{
		if (REDI_RESTAURANT_DEBUG)
		{
			if (!$color)
			{
				echo '<pre>';
				var_dump($object);
				echo '<pre>';
				return;
			}
			$result = highlight_string("<?php\n".print_r($object, TRUE), TRUE);
			echo '<pre style="text-align: left;">'.preg_replace('/&lt;\\?php<br \\/>/', '', $result, 1).'</pre><br />';
		}

	}

	public static function p($object)
	{
		if (REDI_RESTAURANT_DEBUG)
			return $object;
	}
}
