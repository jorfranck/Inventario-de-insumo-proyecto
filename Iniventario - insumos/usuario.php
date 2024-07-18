<?php

//importar conexion
require 'includes/database.php';
$db = conectarDB(); 

//Crear un usuario y password
$email = 'admin@gmail.com';
$password = 'admin';

$passwordHast = password_hash($password, PASSWORD_DEFAULT);

//QUERY PARA CREAR USUARIOS
$query = " INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHast}'); ";

//INSERTAR A LA BASE DE DATOS
mysqli_query($db, $query);

