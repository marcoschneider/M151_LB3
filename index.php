<?php
	define('TYPE_MYSQL', 'mysql');
	define('TYPE_POSTGRES', 'postgres');
	
	require "vendor/autoload.php";
	require "Route.php";
	require "src/SessionManager.php";
	require "src/service/GetPlacesService.php";
	require "src/Logger.php";
	require "src/Config.php";
	SessionManager::startSession();
	
	$database = Config::getConnection(TYPE_MYSQL);
	$database->connect();
	
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
		
			Route::add('/places', function () {
			include "public/view/places.html";
		});
		
		Route::add('/students/places/to-update', function () use ($database) {
			$placesService = new GetPlacesService($database, new Logger());
			$hasPlacesToUpdate = $placesService->getPlacesFromStudentsToUpdate();
			echo $hasPlacesToUpdate;
		});
		
		Route::add('/logout', function () {
			SessionManager::closeSession();
			header('Location: login');
		});
		
		Route::add('/users', function () {
			if (SessionManager::isSessionSet()) {
				include "public/view/users.html";
			}
		});
	}
	else {
		Route::add('/', function () use ($database) {
			echo "Es konnte keine Verbindung zur Datenbank hergestellt werden " . $database;
		});
	}
	
	Route::run(Config::webRoot());