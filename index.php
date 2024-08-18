<?php 

require 'db.php';
require 'funciones.php';

$db = conectarDB();

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    

    $correo = mysqli_real_escape_string($db, filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if(!$correo) {
        $errores[] = "El email es obligatorio o no es válido";
    }

    if(!$password) {
        $errores[] = "La contraseña es obligatoria";
    }

    if(empty($errores)) {
        $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $resultado = mysqli_query($db, $query);

        if($resultado->num_rows) {

            $usuario = mysqli_fetch_assoc($resultado);

            $auth = password_verify($password, $usuario['password']);

            if($auth) {

                session_start();

                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['login'] = true;

                header('Location:  tienda.php');

            } else {
                $errores[] = "La contraseña es incorrecta";
            }
        } else {
            $errores[] = "El usuario no existe";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/loginstyles.css">
</head>
<body>
<div class="container">

    <?php foreach ($errores as $error): ?>
        <p class="error"><?= $error ?></p>
    <?php endforeach; ?>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="correo" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
    </div>
</div>

</body>
</html>
