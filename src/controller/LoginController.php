<?php
	
	require_once "SessionManager.php";
	
	class LoginController
	{

		private $database;
		private $logger;
		
		public function __construct(PDO $database, Logger $logger)
		{
			$this->database = $database;
			$this->logger = $logger;
		}
		
		private function validateLoginForm($data) {
			if ($data->email != '' && $data->password != '') {
				$email = htmlspecialchars($data->email);
				$password = hash('sha512', htmlspecialchars($data->password));
				
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
				$sql = '
				SELECT
      	 	id,
				 	email
				FROM m_151_studentmap.users
				WHERE email = ?
				AND pass = ?';

				try {
					$stmt = $this->database->prepare($sql);
					$stmt->execute([
						$values['email'],
						$values['password'],
					]);
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