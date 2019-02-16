<?php
	
	class SessionManager
	{
		
		public static function startSession() {
			session_start([
				"name" => "M151_STUDENTS_MAP",
				"cookie_domain" => getenv('HTTP_HOST')
			]);
		}
		
		public static function setupUserSession($user) {
			$token= md5(microtime().rand());
			$_SESSION['kernel']['token'] = $token;
			$_SESSION['kernel']['user'] = $user;
		}
		
		public static function closeSession() {
			session_unset();
			session_destroy();
		}
		
		public function __get($name)
		{
			return $_SESSION[$name];
		}
		
		public static function getToken() {
			if (isset($_SESSION['kernel'])) {
				return $_SESSION['kernel']['token'];
			}
			return false;
		}
		
		public static function getCurrentSession() {
			if (isset($_SESSION)){
				return $_SESSION;
			}
			return false;
		}
	}