<?php

require 'db.php';
require 'funciones.php';

$db = conectarDB();

$query = "SELECT * FROM productos";
$resultadoConsulta = mysqli_query($db, $query);

session_start();

if (!$_SESSION['login']) {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">

    <title>Integración ePayco</title>
</head>

<body>
    <div class="container">
        <h1>Nuestros Productos</h1>
        <div class="products">
            <?php while ($producto = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                <div class="product">
                    <img src="img/<?= $producto['imagen'] ?>" alt="Producto de prueba">
                    <h2><?= $producto['nombre'] ?></h2>
                    <p>Precio: $<?= $producto['precio'] ?></p>
                    <p><?= $producto['descripcion'] ?></p>
                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="codigo_producto" value="<?= $producto['codigo'] ?>">
                        <input type="hidden" name="nombre_producto" value="<?= $producto['nombre'] ?>">
                        <input type="hidden" name="descripcion" value="<?= $producto['descripcion'] ?>">
                        <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                        <button type="submit">Comprar</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    
    </div>
</body>

</html>