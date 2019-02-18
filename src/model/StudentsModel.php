<?php
	
class StudentsModel {
	
	private $database;
	private $logger;
	
	public function __construct(PDO $database, Logger $logger)
	{
		$this->database = $database;
		$this->logger = $logger;
	}
	
	public function addStudent($values)
	{
		SessionManager::startSession();
		$values['userid'] = SessionManager::getUserSession()['id'];
		if (count($values['error']) === 0) {
			$sql = "
			INSERT INTO
				students(firstname, lastname, fk_placeid, fk_user)
				VALUES (?, ?, ?, ?)
		";
			try{
				$result = $this->database->prepare($sql)
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
			UPDATE students
				SET
				firstname = ?,
				lastname = ?,
				fk_placeid = ?
			WHERE
				studentsid = ?
			AND
				fk_user = ?
		";
			try{
				$result = $this->database->prepare($sql)
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
			FROM m_151_studentmap.students
			INNER JOIN place p on students.fk_placeid = p.placeid
			WHERE fk_user = ?
		";
		
		$stmt = $this->database->prepare($sql);
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
			DELETE FROM students WHERE studentsid = ? AND fk_user = ?
		';
		try {
			$stmt = $this->database->prepare($sql);
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