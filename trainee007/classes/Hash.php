<?php
/**
 * Hash password function.
 * 
 * @category Hash
 */
	class Hash {
    /**
     * Method use hash function to secure password before saved to database.
     * 
     * @param string $string Password is need to hash
     * @param string $salt This string is added before used hash function
     * @return string Password after used hash function 
     */
		public static function make($string, $salt = '') {
			return hash('sha256', $string.$salt);
		}
    /**
     * 
     * @param string $length
     * @return IV|false
     */
		public static function salt($length) {
			return mcrypt_create_iv($length);
		}
    /**
     * 
     * @return string
     */
		public static function unique() {
			return self::make(uniqid());
		}
	}
?>