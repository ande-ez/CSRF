<?php

/**
 * Cross Site Request Forgery protection class.
 *
 */
class CSRF {

	// ---------------------------------------------------------------------------
	/**
	 * Singleton style initialize CSRF token
	 *
	 */
	public static function init() {
		// Check if session engine is started
		if (session_status() == PHP_SESSION_NONE) {
			throw new Exception('Configuration failure: PHP session engine not started!');
		}

		static $tokenInitialized = FALSE;
		if($tokenInitialized == FALSE) {
			// Set token if not set
			if(!isset($_SESSION['token'])) {
				$_SESSION['token'] = CSRF::generateNewToken();
			}
			$tokenInitialized = TRUE;
		}
	}


	// ---------------------------------------------------------------------------
	/**
	 * Generates a random string of 100 characters for the CSRF token
	 *
	 */
	private static function generateNewToken() {
		srand(time());
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ret = '';
		for ($p = 0; $p < 100; $p++) {
			$ret .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		return $ret;
	}


	// ---------------------------------------------------------------------------
	/**
	 * Validates CSRF token for POST requests
	 *
	 */
	public static function validatePost() {
		// Check that the POST 'token' input is set
		if(!isset($_POST['token'])) {
			return FALSE;
		}

		// Valudate external and internal token
		return CSRF::validateToken($_POST['token']);
	}


	// ---------------------------------------------------------------------------
	/**
	 * Validates CSRF token for GET requests
	 *
	 */
	public static function validateGet() {
		// Check that the GET 'token' input is set
		if(!isset($_GET['token'])) {
			return FALSE;
		}

		// Valudate external and internal token
		return CSRF::validateToken($_GET['token']);
	}


	// ---------------------------------------------------------------------------
	/**
	 * Validates a CSRF token (string) against the session token
	 *
	 */
	private static function validateToken($token) {
		// Make sure the SESSION 'token' input is set
		if(!isset($_SESSION['token'])) {
			throw new Exception('Configuration failure: Internal SESSION token not set.');
		}

		// Valudate $token against internal session token
		if($token === $_SESSION['token']) {

			// New token for each time it is used
			$token = CSRF::generateNewToken();
			$_SESSION['token'] = $token;

			// Token was correct
			return TRUE;

		}

		// Not valid
		return FALSE;
	}


	// ---------------------------------------------------------------------------
	/**
	 * Prints the hidden token input as HTML
	 *
	 */
	public static function tokenInput() {
		// Make sure the SESSION 'token' input is set
		if(!isset($_SESSION['token'])) {
			CSRF::init();
		}

		// Return a hidden HTML input for the token
		print '<input type="hidden" id="token" name="token" value="'.$_SESSION['token'].'" />';
	}


	// ---------------------------------------------------------------------------
	/**
	* Returns the current token
	*
	*/
	public static function getToken() {
		// Make sure the SESSION 'token' input is set
		if(!isset($_SESSION['token'])) {
			CSRF::init();
		}

		// Return token
		return $_SESSION['token'];
	}

}
