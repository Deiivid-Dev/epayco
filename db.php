<?php

function conectarDB()
{
    $db = mysqli_connect('localhost', 'root', 'root', 'productos');

    if (!$db) {
        mysqli_connect_error('Error en la conexión');
    }

    return $db;
}
