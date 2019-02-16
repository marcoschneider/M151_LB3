<?php
	
	class Ajax
	{
		
		private $loginController;
		private $studentsModel;
		private $registerController;
		private $placesModel;
		private $placesService;
		
		public function __construct(
			LoginController $loginController,
			StudentsModel $studentsModel,
			RegisterController $registerController,
			PlacesModel $placesModel,
			GetPlacesService $placesService
		)
		{
			$this->loginController = $loginController;
			$this->studentsModel = $studentsModel;
			$this->registerController = $registerController;
			$this->placesModel = $placesModel;
			$this->placesService = $placesService;
		}
		
		public function getRequest()
		{
			header('HTTP/1.0 200 OK');
			header('Content-Type: application/json');
			if (isset($_REQUEST['json_data'])) {
				$data = json_decode($_REQUEST['json_data']);
				$trigger = $data->trigger;
				return $this->handleRequest($trigger, $data);
			}
			return "Couldn't handle json data";
		}
		
		private function handleRequest($trigger, $data) {
			$result = '';
			switch ($trigger) {
				case 'login':
					$result = $this->loginController->authenticate($data);
					break;
				case 'register':
					$result = $this->registerController->register($data);
					break;
				case 'getAllStudents':
					$result = $this->studentsModel->getAllStudents();
					break;
				case 'getAllPlaces':
					$result = $this->placesModel->getAllPlaces();
					break;
				case 'addStudent':
					$result = $this->studentsModel->addStudent($data);
					break;
				case 'deleteStudent':
					$result = $this->studentsModel->deleteStudent($data);
					break;
				case 'editStudent':
					$result = $this->studentsModel->updateStudent($data);
					break;
				case 'update-places':
					$result = $this->placesService->updatePlaces();
					break;
			}
			return $this->sendResponse($result);
		}
		
		private function sendResponse($result) {
			if (isset($result)) {
				return json_encode($result, JSON_PRETTY_PRINT);
			}
			return 'Error';
		}
	}