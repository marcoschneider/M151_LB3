<?php
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\GuzzleException;
	
	class PlacesModel {
		
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
		
		public function addPlace($data) {
			if (empty($data['error'])) {
				$client = new Client();
				try {
					$response = $client->request('GET', "https://nominatim.openstreetmap.org/search?q=" . $data['placename'] . ",Schweiz&format=json");
				}catch (GuzzleException $exception) {
					return $exception->getMessage();
				}
				$body = $response->getBody();
				$places = json_decode($body, JSON_OBJECT_AS_ARRAY);
				
				$sql = "
				INSERT INTO {drivers_schema_name}.place(placeid, placename, latitude, longitude)
				VALUES (?,?,?,?)
			";
				$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
			}
			return $data['error'];
		}
		
		/**
		 * Gets all places.
		 *
		 * @return array
		 *   Returns array of all places
		 */
		public function getAllPlaces() {
			$sql = "
				SELECT
					placeid,
					placename,
					latitude,
					longitude
				FROM {drivers_schema_name}.place
			";
			$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
			$query = $this->connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$results = $query->fetchAll();
			
			$value = [];
			foreach($results as $key => $result) {
				$value[$result['placeid'] . ' ' . $result['placename']] = null;
			}
			$data = ['data' => $value];
			return $data;
		}
	}