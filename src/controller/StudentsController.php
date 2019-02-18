<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-02-08
	 * Time: 23:19
	 */
	
	require_once "SessionManager.php";
	
	class StudentsController
	{
		private $studentsModel;
		
		public function __construct(StudentsModel $studentsModel) {
			$this->studentsModel = $studentsModel;
		}
		
		public function getAllStudents() {
			return $this->studentsModel->getAllStudents();
		}
		
		public function addStudent($data) {
			$values = $this->validateStudentsData($data);
			return $this->studentsModel->addStudent($values);
		}
		
		public function updateStudent($data) {
			$values = $this->validateStudentsData($data);
			return $this->studentsModel->updateStudent($values);
		}
		
		public function deleteStudent($data) {
			return $this->studentsModel->deleteStudent($data);
		}
		
		public function validateStudentsData($data) {
			$form_values = $data->values;
			$values = ['error' =>[]];
			if ($form_values->firstname != '') {
				$values['firstname'] = htmlspecialchars($form_values->firstname);
			}else{
				$values['error'][] = "Vorname nicht angegeben";
			}
			
			if ($form_values->lastname != '') {
				$values['lastname'] = htmlspecialchars($form_values->lastname);
			}else{
				$values['error'][] = "Nachname nicht angegeben";
			}
			
			if (isset($data->studentId)) {
				$values['studentid'] = htmlspecialchars($data->studentId);
			}
			
			if ($form_values->place != '') {
				$place = htmlspecialchars($form_values->place);
				$place = explode(' ', $place);
				$values['plz'] = $place[0];
			}else{
				$values['error'][] = "Wohnort nicht angegeben";
			}
			return $values;
		}
	}