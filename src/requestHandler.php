<?php
	
	// Includes
	require "../vendor/autoload.php";
	require "Ajax.php";
	require "Logger.php";
	require "connector.inc.php";
	require "service/GetPlacesService.php";
	require "controller/LoginController.php";
	require "controller/RegisterController.php";
	require "controller/StudentsController.php";
	require "controller/PlacesController.php";
	require "model/StudentsModel.php";
	require "model/PlacesModel.php";

	$logger = new Logger();
	if (is_object($database)) {
		$ajax = new Ajax(
			new LoginController($database, $logger),
			new StudentsController(new StudentsModel($database, $logger)),
			new RegisterController($database, $logger),
			new PlacesController(new PlacesModel($database, $logger)),
			new GetPlacesService($database, $logger)
		);
		$result = $ajax->getRequest();
		echo $result;
	}else{
		$logger->writeLog("Es konnte keine Verbindung zur Datenbank hergestellt werden " . $database);
		echo "Please watch the logs /src/log/error_log.txt";
	}
	
