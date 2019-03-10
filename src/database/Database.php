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
		
		public function __construct($type) {
			$this->type = $type;
		}
		
		/**
		 * Creates connection.
		 */
		public function connect() {
			switch ($this->type) {
				case TYPE_MYSQL:
					$values = Config::getDatabaseCredentials($this->type);
					$this->schema_name = $values['schema_name'];
					$this->setupConnection($values['username'], $values['password'], $values['connection_string']);
					break;
				case TYPE_POSTGRES:
					$values = Config::getDatabaseCredentials($this->type);
					$this->schema_name = $values['schema_name'];
					$this->setupConnection($values['username'], $values['password'], $values['connection_string']);
					break;
			}
		}
		
		private function setupConnection($username, $password, $connection_string) {
			try{
				$database = new PDO($connection_string, $username, $password);
				$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch (PDOException $exception) {
				$database = $exception->getMessage();
			}
			$this->connection = $database;
		}
	}