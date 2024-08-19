<?php 

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
    <script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>
    <title>Realizar pago</title>
</head>

<?php

$codigo_producto = '';
$nombre_producto = '';
$descripcion = '';
$precio = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_producto = $_POST['codigo_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
?>

<?php
    ?>  
    <div class="centrar">
        <h1>Bienvenido: <?= $_SESSION['nombre'] ?></h1>
        <h2>Estás comprando: <?= $nombre_producto ?></h2>
        <h3>Descripcion: <?= $descripcion ?></h3>
        <h4>Precio: $<?= $precio ?></h4>
        <p>¡Gracias por tu compra!</p>
    </div>
<?php
    // Simular que el pago se ha realizado con éxito
} else {
    // Si el usuario accede directamente a la página de checkout sin pasar por el formulario
    echo "<p>No has seleccionado ningún producto.</p>";
}
?>
<body>
    <script>
        var handler = ePayco.checkout.configure({
            key: '283b6d92dff1765138edc55c01005f48',
            test: true
        });

        var data = {
            //Parametros compra (obligatorio)
            name: "<?= $nombre_producto ?>",
            description: "<?= $descripcion ?>",
            invoice: "FAC-1263",
            currency: "cop",
            amount: "<?= $precio ?>",
            country: "co",
            lang: "es",

            //Onpage="false" - Standard="true"
            external: "false",

            //Atributos opcionales
            confirmation: 'https://24fe-2800-e2-bd80-eb5-c8d3-6f2b-f35b-6f25.ngrok-free.app/epayco/confirmacion.php',
            response: 'https://24fe-2800-e2-bd80-eb5-c8d3-6f2b-f35b-6f25.ngrok-free.app/epayco/respuesta/respuesta.html',

            //Atributos cliente
            name_billing: "<?= $_SESSION['nombre'] ?>",
            email_billing: "<?= $_SESSION['correo'] ?>",

            //atributo deshabilitación método de pago
            methodsDisable: []
        }
    </script>
</body>

</html>

<button onclick="handler.open(data)">Proceder con el pago</button>