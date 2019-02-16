<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-02-08
	 * Time: 23:19
	 */
	
	class StudentsController
	{
		public function __construct()
		{
		}
		
		public function validateStudentsData($data) {
			$data = $data->values;
			$values = ['error' =>[]];
			if ($data->firstname != '') {
				$values['firstname'] = htmlspecialchars($data->firstname);
			}else{
				$values['error'][] = "Vorname nicht angegeben";
			}
			
			if ($data->lastname != '') {
				$values['lastname'] = htmlspecialchars($data->lastname);
			}else{
				$values['error'][] = "Nachname nicht angegeben";
			}
			
			if ($data->place != '') {
				$place = htmlspecialchars($data->place);
				$place = explode(' ', $place);
				$values['plz'] = $place[0];
			}else{
				$values['error'][] = "Wohnort nicht angegeben";
			}
			return $values;
		}
	}