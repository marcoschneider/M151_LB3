<?php
	
	class Logger
	{
		public function writeLog($message){
			
			$message = $message.PHP_EOL.date('dd.mm.yyyy', time()).PHP_EOL;
			
			$filename = dirname(__DIR__)."/src/log/error_log.txt";
			
			if (file_exists($filename)) {
				file_put_contents($filename, $message, FILE_APPEND);
			}
			else {
				fopen($filename, "wb");
				file_put_contents($filename, $message);
			}
		}
	}