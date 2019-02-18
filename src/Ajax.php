<?php
	
	require_once "SessionManager.php";
	
	class Ajax
	{
		
		private $loginController;
		private $studentController;
		private $registerController;
		private $placesController;
		private $placesService;
		
		public function __construct(
			LoginController $loginController,
			StudentsController $studentController,
			RegisterController $registerController,
			PlacesController $placesController,
			GetPlacesService $placesService
		)
		{
			$this->loginController = $loginController;
			$this->studentController = $studentController;
			$this->registerController = $registerController;
			$this->placesController = $placesController;
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
					$result = $this->studentController->getAllStudents();
					break;
				case 'getAllPlaces':
					$result = $this->placesController->getAllPlaces();
					break;
				case 'addStudent':
					$result = $this->studentController->addStudent($data);
					break;
				case 'deleteStudent':
					$result = $this->studentController->deleteStudent($data);
					break;
				case 'editStudent':
					$result = $this->studentController->updateStudent($data);
					break;
				case 'update-places':
					$result = $this->placesService->updatePlaces();
					break;
				case 'sadlkfjalfjsdalfj':
					SessionManager::startSession();
					$result = SessionManager::isSessionSet();
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