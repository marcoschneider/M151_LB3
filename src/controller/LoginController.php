<?php
	
	require_once "SessionManager.php";
	
	class LoginController
	{

		private $database;
		private $connection;
		private $logger;
		
		public function __construct(Database $database, Logger $logger)
		{
			$this->connection = $database->connection;
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
				FROM {drivers_schema_name}.users
				WHERE email = ?
				AND pass = ?';
				$sql = str_replace('{drivers_schema_name}', $this->database->schema_name, $sql);
				try {
					$stmt = $this->connection->prepare($sql);
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