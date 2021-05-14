<?php
/**
 * API
 * @copyright Copyright © 2021 Steez Webdevelopment. All rights reserved.
 * @author    tommy@steez.nl
 */

class CwApi
{
	
	/** @var string */
	const API_URL = 'https://api.cloudways.com/api/v1';
	
	/**
	 * @var $api_key string
	 */
	private $api_key;
	
	/**
	 * @var $email string
	 */
	private $email;
	
	/**
	 * API constructor.
	 * @param $email
	 * @param $api_key
	 */
	public function __construct (
		$email,
		$api_key
	) {
		$this->email = $email;
		$this->api_key = $api_key;
	}
	
	/**
	 * @param $method
	 * @param $url
	 * @param $access_token
	 * @param array $post
	 * @return mixed
	 */
	public function call_cloudways_api ($method, $url, $access_token, $post = [])
	{
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_URL, self::API_URL . $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		//Set Authorization Header
		if ($access_token) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
		}
		
		//Set Post Parameters
		$encoded = '';
		if (count($post)) {
			foreach ($post as $name => $value) {
				$encoded .= urlencode($name) . '=' . urlencode($value) . '&';
			}
			$encoded = substr($encoded, 0, strlen($encoded) - 1);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded);
			curl_setopt($ch, CURLOPT_POST, 1);
		}
		
		$output = curl_exec($ch);
		
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpcode != '200') {
			die('An error occurred code: ' . $httpcode . ' output: ' . substr($output, 0, 10000));
		}
		curl_close($ch);
		
		return json_decode($output);
	}
}
