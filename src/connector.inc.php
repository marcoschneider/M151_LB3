<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 22.12.2018
 * Time: 15:46
 */

$server = 'localhost';
$username = 'm_151';
$password = 'toor';
$dbname = 'm_151_studentmap';

try{
  $conn = new PDO("mysql:host=$server;$dbname", $username,$password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
  echo "Error: " . $exception->getMessage();
}