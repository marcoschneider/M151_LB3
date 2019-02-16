<?php
	
	require "vendor/autoload.php";
	require "Route.php";
	require "src/SessionManager.php";
	require "src/service/GetPlacesService.php";
	require "src/connector.inc.php";
	require "src/Logger.php";
	require "src/Config.php";
	
	SessionManager::startSession();
	
	if (is_object($database)) {
		Route::add('/', function () {
			include "public/view/frontpage.html";
		});
		
		Route::add('/login', function () {
			$token = SessionManager::getToken();
			if ($token) {
				header('Location: '.Config::webRoot());
			}else{
				include "public/view/login.html";
			}
		});
		
		Route::add('/register', function () {
			include "public/view/register.html";
		});
		
		Route::add('/places/update', function () use ($database) {
			$placesService = new GetPlacesService($database, new Logger());
			$updateMessage = $placesService->updatePlaces();
			echo $updateMessage;
		});
		
		Route::add('/logout', function () {
			SessionManager::closeSession();
			header('Location: login');
		});
	}
	else {
		Route::add('/', function () use ($database) {
			echo "Es konnte keine Verbindung zur Datenbank hergestellt werden " . $database;
		});
	}
	
	Route::run(Config::webRoot());