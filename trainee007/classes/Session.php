<?php
/**
 * @category Session
 */
	class Session {
    /**
     * Check session name exist or not.
     * 
     * @param string $name
     * @return boolean
     */
		public static function exists($name) {
			return (isset($_SESSION[$name])) ? true : false;
		}
    /**
     * Set value session.
     * 
     * @param string $name variable of session
     * @param string $value value correspond to the name
     * @return string
     */
		public static function put($name, $value) {
			return $_SESSION[$name] = $value;
		}
    /**
     * Return value of Session correspond to the name.
     * 
     * @param string $name
     * @return string
     */
		public static function get($name) {
			return $_SESSION[$name];
		}
    /**
     * Unset value of session.
     * 
     * @param string $name
     */
		public static function delete($name) {
			if (self::exists($name)) {
				unset($_SESSION[$name]);
			}
		}
    /**
     * Check out if session is existed, to unset it or set it to variable.
     * 
     * @param string $name
     * @param string $string
     * @return string
     */
		public static function flash($name, $string = '') {
			if (self::exists($name)) {
				$session = self::get($name);
				self::delete($name);
				return $session;
			} else {
				self::put($name, $string);
			}
		}
	}	
?>
