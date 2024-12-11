<?php

//importar conexion
require 'includes/database.php';
$db = conectarDB(); 

//Crear un usuario y password
$cedula = '30346174';
$username = 'admin';
$password = 'admin';

$passwordHast = password_hash($password, PASSWORD_DEFAULT);

//QUERY PARA CREAR USUARIOS
$query = "INSERT INTO usuarios (cedula, username, password) VALUES ('${cedula}', '${username}','${passwordHast}'); ";

//INSERTAR A LA BASE DE DATOS
mysqli_query($db, $query);

