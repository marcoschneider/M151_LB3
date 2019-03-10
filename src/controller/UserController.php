<?php
	/**
	 * Created by PhpStorm.
	 * User: maschneider
	 * Date: 2019-02-18
	 * Time: 18:07
	 */
	
	class UserController
	{
		
		private $userModel;
		
		public function __construct(UserModel $userModel)
		{
			$this->userModel = $userModel;
		}
		
		public function getAllUsers()
		{
			return $this->userModel->getAllUsers();
		}
		
	}