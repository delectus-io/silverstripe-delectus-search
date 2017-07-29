<?php

/**
 * Delectus module class holds configuration and common functionality for the delectus module. Provides an interface which can be used internally
 * and by other code on the site.
 */
class Delectus extends Object {
	/**
	 * Set to the client token you have been allocated, used to communicate with the delectus service
	 *
	 * @var string
	 */
	private static $client_token = '';
	/**
	 * Set to the client secret you have been allocated, used to communicate with the delectus service
	 *
	 * @var string
	 */
	private static $client_secret = '';
	/**
	 * Set to the client salt you have been allocated for individual encryption of information
	 *
	 * @var string
	 */
	private static $salt = '';

	private static $curl_options = [
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_PORT           => 443,
		CURLOPT_USE_SSL        => true,
		CURLOPT_HEADER         => [
			// key can be used to set this to something else in config, the header is send as specified as the value
			'ContentType' => 'ContentType:application/json',
		],
	];

	public function add_page( $page ) {
		$page = is_int( $page )
			? SiteTree::get()->byID( $page )
			: $page;

		if ( $page && $page->exists() ) {
			if ( $page->ShowInSearch ) {
				$url = Controller::join_links(
					Director::absoluteBaseURL(),
					$page->Link()
				);
				static::make_request(
					static::endpoint( self::AddToIndex ),

				)
			}
		}
	}

	public function remove_page( $page ) {

	}

	public function add_file( $file ) {

	}

	public function remove_file( $file ) {

	}

	public function search() {

	}

	protected static function endpoint() {

	}

	/**
	 * @param       $url     url to call
	 * @param array $params  will be added onto the url as query string
	 * @param array $data    if present then request will be a POST with json_encoded data
	 * @param array $options additional options to merge into curl
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected static function make_request( $url, $params = [], $data = [], $options = [] ) {
		$ch     = null;
		$result = null;

		try {
			$ch = curl_init( $url );

			curl_setopt_array( $ch, static::curl_options( $data, $options ) );
			curl_setopt_array( $ch, static::curl_headers() );

			$response = curl_exec( $ch );
			if ( $response === false ) {
				throw new Exception( "Error: " . curl_error( $ch ) );
			}
			$responseCode = curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );
			if ( $responseCode != 200 ) {
				throw new Exception( "Failed response code: $responseCode" );
			}
			$contentType = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
			if ( $contentType != 'application/json' ) {
				throw new Exception( "Bad content type: $contentType" );
			}

			$result = static::decode( $response, $contentType );

		} finally {
			if ( $ch ) {
				curl_close( $ch );
			}
		}

		return $result;
	}

	protected static function encode( $data, $contentType = 'application/json' ) {
		return json_encode( $data );
	}

	protected static function decode( $body, $contentType ) {
		return json_decode( $body, true );
	}

	protected static function curl_options( $data, $options = [], $headers = [] ) {
		$options = array_merge(
			static::config()->get( 'curl_options' ),
			$options
		);
		if ( $data ) {
			$options[ CURLOPT_POST ]       = true;
			$options[ CURLOPT_POSTFIELDS ] = static::encode( $data );
		} else {
			$options[ CURLOPT_HTTPGET ] = true;
		}

		return $options;

	}

	protected static function curl_headers() {
		return [
			CURLOPT_HEADER => array_merge(
				static::config()->get( 'curl_headers' ) ?: [],

				)
		];
	}
}