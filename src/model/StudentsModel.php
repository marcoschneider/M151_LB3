<?php

class StudentsModel
{
	private $database;
	private $studentsController;
	private $logger;
	
	public function __construct($database, StudentsController $studentsController, Logger $logger)
	{
		$this->database = $database;
		$this->studentsController = $studentsController;
		$this->logger = $logger;
	}
	
	public function addStudent($data)
	{
		$values = $this->studentsController->validateStudentsData($data);
		if (count($values['error']) === 0) {
			$sql = "
			INSERT INTO
				students(firstname, lastname, fk_placeid)
				VALUES (?, ?, ?)
		";
			try{
				$result = $this->database->prepare($sql)
					->execute([
						$values['firstname'],
						$values['lastname'],
						$values['plz'],
					]);
				if ($result) {
					return true;
				}else{
					throw new PDOException("Neuer Benutzer konnte nicht gespeichert werden");
				}
			}catch (PDOException $e) {
				return $e->getMessage();
			}
		}
		return $values['error'];
	}
	
	public function getAllStudents()
	{
		$sql = "
			SELECT
				studentsid,
				firstname,
				lastname,
				p.placename,
				p.placeid,
				p.latitude,
				p.longitude
			FROM students
			INNER JOIN place p on students.fk_placeid = p.placeid
		";
		
		$stmt = $this->database->query($sql);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		if ($result) {
			return $result;
		}
		return [];
	}
	
	public function deleteStudent($data)
	{
		$sql = '
			DELETE FROM students WHERE studentsid = ?
		';
		try {
			$stmt = $this->database->prepare($sql);
			$stmt->execute([
				$data->studentid
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