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
		
		public static function getUserSession() {
			if (isset($_SESSION['kernel']['user'])){
				return $_SESSION['kernel']['user'];
			}
			return false;
		}
		
		public static function getToken() {
			if (isset($_SESSION['kernel'])) {
				return $_SESSION['kernel']['token'];
			}
			return false;
		}
		
		public static function isSessionSet() {
			if (!empty($_SESSION)) {
				return true;
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