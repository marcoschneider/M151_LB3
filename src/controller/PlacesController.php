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
		
	}