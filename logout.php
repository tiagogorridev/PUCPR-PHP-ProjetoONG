<?php
session_start(); 

session_unset();   
session_destroy(); 

header("Location: telas/usuarios/usu-login.php");
exit();
