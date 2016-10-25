<?php
/**
 * Set cookie.
 * 
 * @category Cookie
 */
	class Cookie {
    /**
     * Check cookie name exist or not.
     * 
     * @param string $name 
     * @return boolean
     */
		public static function exists($name) {
			return (isset($_COOKIE[$name])) ? true : false;
		}
    /**
     * get cookie name.
     * 
     * @param string $name
     * @return string Save cookie name to $_COOKIE global variable
     */
		public static function get($name) {
			return $_COOKIE[$name];
		}
    /**
     * Function Which set cookie name,value,time and path.
     * 
     * @param string $name Name of variable that cookie will save 
     * @param mixed $value Value of cookie variable 
     * @param int $expiry Set lifetime of cookie
     * @return boolean
     */
		public static function put($name, $value, $expiry) {
			if (setcookie($name, $value, time()+$expiry, '/')) {
				return true;
			}
			return false;
		}
    /**
     * Delete cookie
     * 
     * @param string $name Destroy value of cookie name
     */
		public static function delete($name) {
			self::put($name, '', time()-1);
		}
	}
?>
