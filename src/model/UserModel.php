<?php
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\GuzzleException;
	
	class UserModel {
		
		/**
		 * PDO object.
		 *
		 * @var Database $database
		 *    Database object.
		 */
		private $database;
		
		/**
		 * @var object Logger
		 *    Logger object.
		 */
		private $logger;
		
		/**
		 * @var PDO Connection
		 *   PDO object.
		 */
		private $connection;
		
		/**
		 * PlacesModel constructor.
		 *
		 * @param $database
		 *    PDO object.
		 *
		 * @param Logger $logger
		 */
		public function __construct(Database $database, Logger $logger)
		{
			$this->database = $database;
			$this->connection = $database->connection;
			$this->logger = $logger;
		}
		
		/**
		 * Gets all places.
		 *
		 * @return array
		 *   Returns array of all places
		 */
		public function getAllUsers() {
			$sql = "
				SELECT
					id,
					email,
					role
				FROM {drivers_schema_name}.users
			";
			$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
			$query = $this->connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$results = $query->fetchAll();

			return $results;
		}
	}