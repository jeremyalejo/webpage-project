<?php 
	/* PHP Assignment 3 authenticate.php
       Prompts user for authentication
       September 20th 2021
    */
  	define('ADMIN_LOGIN','jeremy'); 
  	define('ADMIN_PASSWORD','Password01'); 

  	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) 
      	|| ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN) 
      	|| ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) { 
   	header('HTTP/1.1 401 Unauthorized'); 
    header('WWW-Authenticate: Basic realm="Our Blog"'); 
    exit("Access Denied: Username and password required."); 
  } 
   
?>
 
