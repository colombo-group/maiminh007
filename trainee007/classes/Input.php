<?php
/**
 * Check the input method.
 * 
 * @category Input
 */
	class Input {
    /**
     * 
     * @param string $type GET or POST method
     * @return boolean
     */
		public static function exists($type = 'post') {
			switch ($type) {
				case 'post':
					return (!empty($_POST)) ? true : false;
					break;
				case 'get':
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;
			}
		}
    /**
     * Return POST or GET method.
     * 
     * @param string $item
     * @return string
     */
		public static function get($item) {
			if (isset($_POST[$item])) {
				return $_POST[$item];
			} else if (isset($_GET[$item])) {
				return $_GET[$item];
			}
			return '';
		}
	}
?>