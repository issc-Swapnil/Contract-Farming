<?php
ob_start(); //Turns on output buffering 
session_start();

$hostdb="localhost";
$userdb="root";
$passdb="";
$database="cf";


$conn = mysqli_connect($hostdb, $userdb, $passdb, $database); //Connection variable

if(mysqli_connect_errno()) 
{
	echo "Failed to connect: " . mysqli_connect_errno();
}

?>