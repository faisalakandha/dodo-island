<?php
session_start();
	/**
	 * Class for handling all calls to the Twitch API
	 *
	 * @author H.A.B.M. Faisal Akandha
	 */
	class eciTwitchApi {
		/**
		 * @var api authorization domain
		 */
		const TWITTER_ID_DOMAIN = 'https://id.twitch.tv/';

		/**
		 * @var api endpoint calls domain
		 */
		const TWITTER_API_DOMAIN = 'https://api.twitch.tv/helix/';

		/**
		 * @var client id
		 */
		private $_clientId;

		/**
		 * @var client secret
		 */
		private $_clientSecret;

		/**
		 * @var access token
		 */
		private $_accessToken;

		/**
		 * @var refresh token
		 */
		private $_refreshToken;

		/**
		 * Constructor for this class
		 *
		 * @param string $clientId twitch client id
		 * @param string $clientSecret twitch client secret
		 * @param string $accessToken twitch access token
		 *
		 * @return void
		 */
		public function __construct( $clientId, $clientSecret, $accessToken = '' ) {
			// set client id
			$this->_clientId = $clientId;

			// set client secret
			$this->_clientSecret = $clientSecret;

			// set access token
			$this->_accessToken = $accessToken;
		}

		/**
		 * Get the login url
		 *
		 * @param array $redirectUri
		 *
		 * @return string
		 */
		public function getLoginUrl( $redirectUri ) {
			// request endpoint
			$endpoint = self::TWITTER_ID_DOMAIN . 'oauth2/authorize';

			// store state so we can check it once the user comes back to our redirect uri
			$_SESSION['twitch_state'] = md5( microtime() . mt_rand() );

			$params = array( // params for endpoint
				'client_id' => $this->_clientId,
				'redirect_uri' => $redirectUri,
				'response_type' => 'code',
				'scope' => 'user:read:subscriptions',
				'state' => $_SESSION['twitch_state']
			);

			// add params to endpoint and return the login url
			return $endpoint . '?' . http_build_query( $params );
		}

		/**
		 * Try and log a user in with Twitch
		 *
		 * @param string $code code from Twitch
		 * @param string $redirectUri redirect uri
		 *
		 * @return array
		 */
		public function tryAndLoginWithTwitch( $code, $redirectUri ) {
			// get access token
			$accessToken = $this->getTwitchAccessToken( $code, $redirectUri );

			// save status and message from access token call
			$status = $accessToken['status'];
			$message = $accessToken['message'];

			global $subscriptionStatus;

			if ( 'ok' == $status ) { // we got an access token1
				// set access token and refresh token class vars 
				$this->_accessToken = $accessToken['api_data']['access_token'];
				$this->_refreshToken = $accessToken['api_data']['refresh_token'];

				// get user info
				$userInfo = $this->getUserInfo();
				$userData = $userInfo['api_data']['data'][0];

				// save status and message from get user info call
				$status = $userInfo['status'];
				$message = $userInfo['message'];

				if ( 'ok' == $userInfo['status'] && isset( $userInfo['api_data']['data'][0] ) ) { // we have user info!
					// log user in with info from get user info api call
					$subscriptionStatus = $this->_getUserSubscription( $userInfo['api_data']['data'][0] );
				}
				
			}

			return array( // return status and message of login
				'status' => $status,
				'message' => $message,
				'sub_status' => $subscriptionStatus,
				'username' => $userData['display_name']
			);
		}

		

		private function _getUserSubscription($apiUserInfo)
		{
			$user_id = $apiUserInfo['id'];
			$userSubInfo = $this->getSubInfo($user_id);
			$result = $userSubInfo['api_data']['status'];
			return $result;
		}


		/**
		 * Get a users info from Twitch
		 *
		 * @param void
		 *
		 * @return array
		 */
		public function getUserInfo() {
			// requet endpoint
			$endpoint = self::TWITTER_API_DOMAIN . 'users';

			$apiParams = array( // params for our api call
				'endpoint' => $endpoint,
				'type' => 'GET',
				'authorization' => $this->getAuthorizationHeaders(),
				'url_params' => array()
			);

			// make api call and return response
			return $this->makeApiCall( $apiParams );
		}

		public function getSubInfo($user_id) {
			// requet endpoint
			$endpoint = self::TWITTER_API_DOMAIN . 'user';

			$apiParams = array( // params for our api call
				'endpoint' => $endpoint,
				'type' => 'SUB',
				'authorization' => $this->getAuthorizationHeaders(),
				'url_params' => array('broadcaster_id' => '781737842', 'user_id' => $user_id)
			);

			// make api call and return response
			return $this->makeApiCall( $apiParams );
		}

		/**
		 * Get authorization header for api call
		 *
		 * @param void
		 *
		 * @return array
		 */
		public function getAuthorizationHeaders() {
			return array( // this array will be used as the header for the api call
				'Client-ID: ' . $this->_clientId,
				'Authorization: Bearer ' . $this->_accessToken
			);
		}

		/**
		 * Get access token
		 *
		 * @param string $code code from Twitch
		 * @param string $redirectUri redirect uri
		 *
		 * @return array
		 */
		public function getTwitchAccessToken( $code, $redirectUri ) {
			// requet endpoint
			$endpoint = self::TWITTER_ID_DOMAIN . 'oauth2/token';

			$apiParams = array( // params for our api call
				'endpoint' => $endpoint,
				'type' => 'POST',
				'url_params' => array(
					'client_id' => $this->_clientId,
					'client_secret' => $this->_clientSecret,
					'code' => $code,
					'grant_type' => 'authorization_code',
					'redirect_uri' => $redirectUri
				)
			);

			// make api call and return response
			return $this->makeApiCall( $apiParams );
		}

		/**
		 * Make calls to the Twitch API
		 *
		 * @param array $params
		 *
		 * @return array
		 */
		public function makeApiCall( $params ) {
			$curlOptions = array( // curl options
				CURLOPT_URL => $params['endpoint'], // endpoint
				//CURLOPT_CAINFO => PATH_TO_CERT, // ssl certificate
				CURLOPT_RETURNTRANSFER => TRUE, // return stuff!
				CURLOPT_SSL_VERIFYPEER => TRUE, // verify peer
				CURLOPT_SSL_VERIFYHOST => 2, // verify host
			);

			if ( isset( $params['authorization'] ) ) { // we need to pass along headers with the request
				$curlOptions[CURLOPT_HEADER] = TRUE;
				$curlOptions[CURLOPT_HTTPHEADER] = $params['authorization'];
			}

			if ( 'POST' == $params['type'] ) { // post request things
				$curlOptions[CURLOPT_POST] = TRUE;
                $curlOptions[CURLOPT_POSTFIELDS] = http_build_query( $params['url_params'] );
			} elseif ( 'GET' == $params['type'] ) { // get request things
				$curlOptions[CURLOPT_URL] .= '?' . http_build_query( $params['url_params'] );
			} elseif ( 'SUB' == $params['type']) {
				$curlOptions[CURLOPT_URL] .= '?' . http_build_query( $params['url_params'] );
			}


			// initialize curl
			$ch = curl_init();

			// set curl options
			curl_setopt_array( $ch, $curlOptions );

			// make call
			$apiResponse = curl_exec( $ch );

			if ( isset( $params['authorization'] ) ) { // we have headers to deal with
				// get size of header
				$headerSize = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );

				// remove header from response so we are left with json body
				$apiResponseBody = substr( $apiResponse, $headerSize );

				// json decode response body
				$apiResponse = json_decode( $apiResponseBody, true );	
			} else { // no headers response is json string
				// json decode response body
				$apiResponse = json_decode( $apiResponse, true );
			}

			// close curl
			curl_close( $ch );

			return array(
				'status' => isset( $apiResponse['status'] ) ? 'fail' : 'ok', // if status then there was an error
				'message' => isset( $apiResponse['message'] ) ? $apiResponse['message'] : '', // if message return it
				'api_data' => $apiResponse, // api response data
				'endpoint' => $curlOptions[CURLOPT_URL], // endpoint hit
				'url_params' => $params['url_params'] // url params sent with the request
			);
		}
	}