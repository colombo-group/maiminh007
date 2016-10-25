<?php
/**
 * Set up database
 * 
 * @category Database
 */
	class Database {
    /**
     *
     * @var string|null instance of 
     */
		private static $_instance = null;
    /**
     *
     * @var object $_pdo initialize connection
     * @var string $_query Save string query to execute
     * @var string $_error Notice error if available, default FALSE
     * @var array  $_result Save result after query
     */
		private $_pdo,
				$_query,
				$_error = false,
				$_results,
				$_count = 0;

    /**
     * Config infors of database
     * 
     */
		private function __construct() {
			try {
				$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}

    /**
     * 
     * @return object \Database
     */
		public static function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new Database();
			}
			return self::$_instance;
		}
    /**
     * 
     * @param strsing $sql query string which fetch datas from database
     * @param array $params
     * @return \Database
     */
		public function query($sql, $params = array()) {
			$this->_error = false;
			if ($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as $param) {
						$this->_query->bindValue($x, $param);
						$x++;
					}
				}

				if ($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count	= $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}

			return $this;
		}

    /**
     * Fetching entities from database.
     * 
     * @param string $action String name for selecting follow fields name
     * @param string $table Select table name
     * @param type $where Select data with conditional expression
     * @return boolean|\Database
     */
		public function action($action, $table, $where = array()) {
			if (count($where) === 3) {	//Allow for no where
				$operators = array('=','>','<','>=','<=','<>');

				$field		= $where[0];
				$operator	= $where[1];
				$value		= $where[2];

				if (in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE ${field} {$operator} ?";
					if (!$this->query($sql, array($value))->error()) {
						return $this;
					}
				}
			}
			return false;
		}
    /**
     * Fetching data.
     * 
     * @param string $table get data from specific table 
     * @param strng $where Condition of select
     * @return boolean successful or not
     */
		public function get($table, $where) {
			return $this->action('SELECT *', $table, $where); //ToDo: Allow for specific SELECT (SELECT username)
		}
    /**
     * Delete data from table with condition.
     * 
     * @param string $table get data from specific table 
     * @param string $where Condition of select
     * @return boolean successful or not
     */
		public function delete($table, $where) {
			return $this->action('DELETE', $table, $where);
		}
    /**
     * Insert data into database.
     * 
     * @param string $table
     * @param string $fields
     * @return boolean successful or not
     */
		public function insert($table, $fields = array()) {
			if (count($fields)) {
				$keys 	= array_keys($fields);
				$values = null;
				$x 		= 1;

				foreach ($fields as $field) {
					$values .= '?';
					if ($x<count($fields)) {
						$values .= ', ';
					}
					$x++;
				}

				$sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES({$values})";

				if (!$this->query($sql, $fields)->error()) {
					return true;
				}
			}
			return false;
		}
    /**
     * Update table with condition.
     * 
     * @param string $table
     * @param int $id
     * @param array $fields
     * @return boolean successful or not
     */
		public function update($table, $id, $fields = array()) {
			$set 	= '';
			$x		= 1;

			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";
				if ($x<count($fields)) {
					$set .= ', ';
				}
				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
			
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			return false;
		}
    /**
     * Return result of query.
     * 
     * @return array Array of data from query string
     */

		public function results() {
			return $this->_results;
		}
    /**
     * Get the first query which return value of field name.
     * 
     * @return array
     */
		public function first() {
			return $this->_results[0];
		}
    /**
     * Notice if errors
     * @return string
     */
		public function error() {
			return $this->_error;
		}
    /**
     * 
     * @return int this is counter 
     */
		public function count() {
			return $this->_count;
		}
	}
?>