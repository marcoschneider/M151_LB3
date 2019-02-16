<?php
	
	class LoginController
	{

		private $database;
		private $logger;
		
		public function __construct($database, Logger $logger)
		{
			$this->database = $database;
			$this->logger = $logger;
		}
		
		private function validateLoginForm($data) {
			if ($data->email != '' && $data->password != '') {
				$email = $data->email;
				$password = hash('sha512', $data->password);
				
				return [
					'email' => $email,
					'password' => $password
				];
			}
			return false;
		}
		
		public function authenticate($data) {
			$values = $this->validateLoginForm($data);

			if ($values != false) {
				$sql = "
				SELECT
      	 	id,
				 	email
				FROM users
				WHERE email = '" . $values['email'] . "'
				AND pass = '" . $values['password'] . "'";

				try {
					$stmt = $this->database->query($sql);
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$result = $stmt->fetch();
					if (is_array($result)) {
						SessionManager::startSession();
						SessionManager::setupUserSession($result);
						return true;
					}else{
						$this->logger->writeLog($result);
						return false;
					}
				}
				catch (PDOException $exception) {
					$this->logger->writeLog($exception);
					return false;
				}
			}
			return false;
		}
	}