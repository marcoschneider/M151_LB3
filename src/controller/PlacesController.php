<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-02-18
	 * Time: 18:07
	 */
	
	class PlacesController
	{
		
		private $placesModel;
		
		public function __construct(PlacesModel $placesModel)
		{
			$this->placesModel = $placesModel;
		}
		
		public function getAllPlaces() {
			return $this->placesModel->getAllPlaces();
		}
		
		public function addPlace($data) {
			$data = $this->checkAddPlaceForm($data);
			return $this->placesModel->addPlace($data);
		}
		
		public function checkAddPlaceForm($data) {
			$values = ['error' => []];
			if ($data->placename === '' && $data->placeid === '') {
				$values['error'][] = "Mindestens ein Feld ausfüllen!";
			} else {
				$values['placename'] = htmlspecialchars($data->placename);
				$values['placeid'] = htmlspecialchars($data->placeid);
			}
			return $values;
		}
		
	}