<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-01-15
	 * Time: 08:54
	 */
	
	class RegisterController
	{
		
		private $database;
		
		private $logger;
		
		public function __construct(PDO $database, Logger $logger)
		{
			$this->database = $database;
			$this->logger = $logger;
		}
		
		private function validateRegisterForm($data){
			$values = [];
			$errors = [];
			
			if (isset($data->email) && $data->email !== '') {
				if(filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
					$values['email'] = htmlspecialchars($data->email);
				}else{
					$errors[] = "Bitte eine valide E-Mailadresse verwenden";
				}
			}else{
				$errors[] = "Bitte eine E-Mail angeben";
			}
			
			if (isset($data->password) && $data->password !== '') {
				$values['password'] = hash("sha512", htmlspecialchars($data->password));
			}else{
				$errors[] = "Bitte ein Passwort eingeben";
			}
			
			if (empty($errors)){
				return $values;
			}
			$errors['error'] = true;
			return $errors;
		}
		
		public function register($data) {
			$values = $this->validateRegisterForm($data);
			
			if (!isset($values['error'])) {
				$sql = '
					INSERT INTO "m_151_studentmap"."users"(email, pass) VALUES (?,?)';
				try{
					$stmt = $this->database->prepare($sql);
					$stmt->execute([
						$values['email'],
						$values['password']
					]);

					if ($stmt) {
						return true;
					}else{
						throw new PDOException("Couldn't insert data" . $this->database->errorInfo());
					}
				}catch (PDOException $exception) {
					return $exception->getMessage();
				}
			}
			return $values;
		}
	}