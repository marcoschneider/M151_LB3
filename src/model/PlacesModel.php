<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-02-06
	 * Time: 23:12
	 */
	
	class PlacesModel
	{
		
		/**
		 * PDO object.
		 *
		 * @var object $database
		 *    PDO object.
		 */
		private $database;
		
		/**
		 * @var object Logger
		 *    Logger object.
		 */
		private $logger;
		
		/**
		 * PlacesModel constructor.
		 *
		 * @param $database
		 *    PDO object.
		 *
		 * @param Logger $logger
		 */
		public function __construct($database, Logger $logger)
		{
			$this->database = $database;
			$this->logger = $logger;
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
			FROM place
		";
			
			$query = $this->database->query($sql);
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