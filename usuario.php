<?php 

require 'db.php';
$db = conectarDB();

$nombre = 'Jesús González';
$email = 'ospinadavid613@gmail.com';
$password = 'prueba';

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios (nombre, correo, password) VALUES ('$nombre', '$email', '$passwordHash')";

mysqli_query($db, $query);