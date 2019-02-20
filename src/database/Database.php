<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-02-19
	 * Time: 09:50
	 */
	
	class Database
	{
		private $type;
		
		public $connection;
		
		public $schema_name;
		
		public function __construct() {}
		
		/**
		 * Creates connection.
		 * @param $type
		 */
		public function connect($type) {
			switch ($type) {
				case TYPE_MYSQL:
					$values = Config::getDatabaseCredentials($type);
					$this->schema_name = $values['schema_name'];
					$this->setupConnection($values['username'], $values['password'], $values['connection_string'], $type);
					break;
				case TYPE_POSTGRES:
					$values = Config::getDatabaseCredentials($type);
					$this->schema_name = $values['schema_name'];
					$this->setupConnection($values['username'], $values['password'], $values['connection_string'], $type);
					break;
			}
		}
		
		private function setupConnection($username, $password, $connection_string, $type) {
			$this->type = $type;
			try{
				$database = new PDO($connection_string, $username, $password);
				$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch (PDOException $exception) {
				$database = $exception->getMessage();
			}
			$this->connection = $database;
		}
	}