<?php
/**
 * @category User
 */
	class User {
    /**
     *
     * @var object $_db initializing connect to database
     * @var array $_data save the first query is returned
     * @var string $_sessionName
     * @var boolean $_isLoggedIn Check user logged in or not
     */
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;
    /**
     * Initializing method with connect to database,get session,cookie
     * 
     * *First, check session user and get session if available
     * *Then check user is logged in or not.
     * @param string|null $user
     */
		public function __construct($user = null) {
			$this->_db 			= Database::getInstance();
			$this->_sessionName = Config::get('session/sessionName');
			$this->_cookieName 	= Config::get('remember/cookieName');

			if (!$user) {
				if (Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);

					if ($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						self::logout();
					}
				}
			} else {
				$this->find($user);
			}
		}
    /**
     * Update information with the same id.
     * 
     * @param array $fields fields need to be updated
     * @param int|null $id Id user
     * @throws Exception
     */
		public function update($fields = array(), $id = null) {

			if (!$id && $this->isLoggedIn()) {
				$id = $this->data()->ID;
			}
      
			if (!$this->_db->update('users', $id, $fields)) {
				throw new Exception("There was a problem updating your details");
			}
		}
    /**
     * Create user when they registry successfully.
     * 
     * @param array $fields
     * @throws Exception
     */
		public function create($fields = array()) {
			if (!$this->_db->insert('users', $fields)) {
				throw new Exception("There was a problem creating your account");
			}
		}
    /**
     * check user existed in database or not.
     * 
     * @param string|int $user can be id or username
     * @return boolean
     */
		public function find($user = null) {
			if ($user) {
				$fields = (is_numeric($user)) ? 'id' : 'username';	//Numbers in username issues
				$data 	= $this->_db->get('users', array($fields, '=', $user));

				if ($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}
    /**
     * 
     * @param string|null $username
     * @param string|null $password
     * @param boolean $remember
     * @return boolean
     */
		public function login($username = null, $password = null, $remember = false) {
			if (!$username && !$password && $this->exists()) {
				Session::put($this->_sessionName, $this->data()->ID);
			} else {
				$user = $this->find($username);
				if ($user) {
					if ($this->data()->password === Hash::make($password,$this->data()->salt)) {
						Session::put($this->_sessionName, $this->data()->ID);

						if ($remember) {
							$hash = Hash::unique();
							$hashCheck = $this->_db->get('usersSessions', array('userID','=',$this->data()->ID));

							if (!$hashCheck->count()) {
								$this->_db->insert('usersSessions', array(
									'userID' 	=> $this->data()->ID,
									'hash' 		=> $hash
								));
							} else {
								$hash = $hashCheck->first()->hash;
							}
							Cookie::put($this->_cookieName, $hash, Config::get('remember/cookieExpiry'));
						}

						return true;
					}
				}
			}
			return false;
		}
    /**
     * 
     * @param int $key
     * @return boolean
     */
		public function hasPermission($key) {
			$group = $this->_db->get('groups', array('ID', '=', $this->data()->userGroup));
			if ($group->count()) {
				$permissions = json_decode($group->first()->permissions,true);

				if ($permissions[$key] == true) {
					return true;
				}
			}
			return false;
		}
    /**
     * check user existed or not
     * @return boolean
     */
		public function exists() {
			return (!empty($this->_data)) ? true : false;
		}
    /**
     * Logout user
     */
		public function logout() {
			$this->_db->delete('usersSessions', array('userID', '=', $this->data()->ID));
			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}
    /**
     * the first query is returned.
     * 
     * @return array
     */
		public function data() {
			return $this->_data;
		}
    /**
     * Check user is logged in or not.
     * 
     * @return boolean
     */
		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}
	}
?>
