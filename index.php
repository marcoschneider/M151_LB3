<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2018-12-20
	 * Time: 22:30
	 */
	
	require "Route.php";
	
	Route::add('/', function () {
		include "public/view/frontpage.html";
	});
	
	Route::add('/login', function () {
		include "public/view/login.html";
	});
	
	Route::run('/M151_LB3');