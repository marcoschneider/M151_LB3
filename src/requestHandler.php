<?php
	
	// Includes
	require "Ajax.php";
	require "Logger.php";
	require "connector.inc.php";
	require "controller/LoginController.php";
	require "controller/RegisterController.php";
	require "controller/StudentsController.php";
	require "model/StudentsModel.php";
	require "model/PlacesModel.php";
	
	$logger = new Logger();
	if (is_object($database)) {
		$ajax = new Ajax(
			new LoginController($database, $logger),
			new StudentsModel($database, new StudentsController(), $logger),
			new RegisterController($database, $logger),
			new PlacesModel($database, $logger)
		);
		$result = $ajax->getRequest();
		echo $result;
	}else{
		$logger->writeLog("Es konnte keine Verbindung zur Datenbank hergestellt werden " . $database);
		echo "Please watch the logs /src/log/error_log.txt";
	}
	
