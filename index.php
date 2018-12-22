<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2018-12-20
	 * Time: 22:30
	 */
	
	require "Route.php";
	require "src/connector.inc.php";

	$stmt = $conn->prepare("SELECT email FROM m_151_studentmap.users");
	$stmt->execute();

  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

  var_dump($result);

  Route::add('/', function () {
		include "public/view/frontpage.html";
	});
	
	Route::add('/login', function () {
		include "public/view/login.html";
	});
	
	Route::run('/M151_LB3');