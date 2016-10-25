<?php
/**
 * @category Token
 */
	class Token {
    /**
     * Generate token.
     * 
     * @return string
     */
		public static function generate() {
			return Session::put(Config::get('session/tokenName'), md5(uniqid()));
		}
    /**
     * Check token exist or not.
     *
     ** If token existed, to unset and return status.
     * 
     * @param string $token
     * @return boolean
     */
		public static function check($token) {
			$tokenName = Config::get('session/tokenName');

			if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
				Session::delete($tokenName);
				return true;
			} else {
				return false;
			}
		}
	}
?>
