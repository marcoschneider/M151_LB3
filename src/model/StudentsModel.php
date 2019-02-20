<?php
	
class StudentsModel {
	
	private $database;
	private $connection;
	private $logger;
	
	public function __construct(Database $database, Logger $logger)
	{
		$this->database = $database;
		$this->connection = $database->connection;
		$this->logger = $logger;
	}
	
	public function addStudent($values)
	{
		SessionManager::startSession();
		$values['userid'] = SessionManager::getUserSession()['id'];
		if (count($values['error']) === 0) {
			$sql = "
			INSERT INTO
				{drivers_schema_name}.students(firstname, lastname, fk_placeid, fk_user)
				VALUES (?, ?, ?, ?)
		";
			$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
			try{
				$result = $this->connection->prepare($sql)
					->execute([
						$values['firstname'],
						$values['lastname'],
						$values['plz'],
						$values['userid'],
					]);
				if ($result) {
					return true;
				}else{
					throw new PDOException("Neuer Benutzer konnte nicht gespeichert werden " . $result->errorInfo());
				}
			}catch (PDOException $e) {
				$this->logger->writeLog($e->getMessage());
				return $e->getMessage();
			}
		}
		return $values['error'];
	}
	
	public function updateStudent($values) {
		SessionManager::startSession();
		$studentid = SessionManager::getUserSession()['id'];
		if (count($values['error']) === 0) {
			$sql = "
			UPDATE {drivers_schema_name}.students
				SET
					firstname = ?,
					lastname = ?,
					fk_placeid = ?
			WHERE
				studentsid = ?
			AND
				fk_user = ?
		";
			$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
			try{
				$result = $this->connection->prepare($sql)
					->execute([
						$values['firstname'],
						$values['lastname'],
						$values['plz'],
						$values['studentid'],
						$studentid,
					]);
				if ($result) {
					return true;
				}else{
					throw new PDOException("Neuer Benutzer konnte nicht gespeichert werden " . $result->errorInfo());
				}
			}catch (PDOException $e) {
				return $e->getMessage();
			}
		}
		return $values['error'];
	}
	
	public function getAllStudents()
	{
		SessionManager::startSession();
		$studentid = SessionManager::getUserSession()['id'];
		$sql = "
			SELECT
				studentsid,
				firstname,
				lastname,
				p.placename,
				p.placeid,
				p.latitude,
				p.longitude
			FROM {drivers_schema_name}.students
			INNER JOIN {drivers_schema_name}.place p on {drivers_schema_name}.students.fk_placeid = p.placeid
			WHERE fk_user = ?
		";
		$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
		$stmt = $this->connection->prepare($sql);
		$stmt->execute([
			$studentid
		]);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		if ($result) {
			return $result;
		}
		return [];
	}
	
	public function deleteStudent($data)
	{
		SessionManager::startSession();
		$studentid = SessionManager::getUserSession()['id'];
		$sql = '
			DELETE FROM {drivers_schema_name}.students WHERE studentsid = ? AND fk_user = ?
		';
		$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
		try {
			$stmt = $this->connection->prepare($sql);
			$stmt->execute([
				$data->studentid,
				$studentid,
			]);
			
			if ($stmt) {
				return true;
			}else{
				throw new PDOException("Couldn't insert data" . $this->database->errorInfo());
			}
		}catch (PDOException $e) {
			return $e->getMessage();
		}
	}
}