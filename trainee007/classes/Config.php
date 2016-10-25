<?php
/**
 * @category Config
 * 
 */
	class Config {
    /**
     * 
     * @param string|null $path Get path
     * @return boolean
     */
		public static function get($path = null) {
			if ($path) {
				$config = $GLOBALS['config'];
				$path	= explode('/', $path);

				foreach ($path as $bit) {
					if (isset($config[$bit])) {
						$config = $config[$bit];
					}
				}

				return $config;
			}
			
			return false;
		}
	}
?>
