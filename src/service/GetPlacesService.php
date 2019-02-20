<?php
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\GuzzleException;
	
	class GetPlacesService
	{
		
		private $database;
		private $logger;
		private $connection;
		
		public function __construct(Database $database,Logger $logger)
		{
			$this->connection = $database->connection;
			$this->database = $database;
			$this->logger = $logger;
		}
		
		public function getPlacesFromStudentsToUpdate() {
			$sql = '
				SELECT
					place.placeid,
					place.placename
				FROM {drivers_schema_name}.place
				INNER JOIN {drivers_schema_name}.students s on {drivers_schema_name}.place.placeid = s.fk_placeid
				WHERE s.fk_placeid = place.placeid
					AND place.latitude = 0
					AND place.longitude = 0
			';
			$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
			$stmt = $this->connection->query($sql);
			$places = $stmt->fetchAll();
			if (!empty($places)) {
				if (isset($_GET['format']) && $_GET['format'] === 'json') {
					return json_encode($places);
				}
				return $places;
			}
			return json_encode($places);
		}
		
		public function updatePlaces() {
			// Only runs get request when actual places coordinates from students table are missing in the database.
			$places = $this->getPlacesFromStudentsToUpdate();
			
			if ($places) {
				foreach ($places as $place) {
					// Setzt die optionen für die Schnittstellenanfrage.
					$client = new Client();
					try {
						$response = $client->request('GET', "https://nominatim.openstreetmap.org/search?q=" . $place['placeid'] . ",Schweiz&format=json");
					} catch (GuzzleException $e) {
						return $e->getMessage();
					}
					$body = $response->getBody();
					// Encodiert den JSON String in ein PHP Array.
					$file = json_decode($body, JSON_OBJECT_AS_ARRAY);
					// Speichert die längen & breitengrade in eine Variable
					$lat = $file[0]['lat'];
					$lon = $file[0]['lon'];
					
					$sql = "
						UPDATE {drivers_schema_name}.place
							SET
								latitude = ?,
								longitude = ?
						WHERE placeid = ?
					";
					$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
					try{
						$result = $this->connection->prepare($sql)
							->execute([
								$lat,
								$lon,
								$place['placeid']
							]);
						
						if ($result) {
							$places['updated'] = true;
							 $places['places'][] = $place['placeid'] . ' ' . $place['placename'] . ' wurde updated';
						}else{
							throw new PDOException($result->errorInfo());
						}
					}catch (PDOException $e) {
						$this->logger->writeLog($e->getMessage());
						return $e->getMessage();
					}
				}
				return $places;
			}
			else{
				return "Keine Ortschaften haben fehlende Kooridnaten. Es wurden keine neuen Daten geschrieben.";
			}
		}
	}