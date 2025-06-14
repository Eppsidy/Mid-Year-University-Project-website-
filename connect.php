<?php
  error_reporting(E_ALL & ~E_NOTICE);
   $server = "localhost";
   $username = "root";
   $password = "";
   $database = "eppsidy";
   
   $con = new mysqli($server,$username,$password, $database);
   
   if ($con -> connect_error)
   {
	die("Connection failed".$con -> connect_error);
   }

?>