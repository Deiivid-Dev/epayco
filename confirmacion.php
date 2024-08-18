<?php

require 'db.php';

$db = conectarDB();

// Datos de configuración
$p_cust_id_cliente = '1490835';
$p_key             = 'f826e228df59eacfb180433d16951c9843e67442';

// Recibir datos de la solicitud
$x_ref_payco      = $_REQUEST['x_ref_payco'];
$x_transaction_id = $_REQUEST['x_transaction_id'];
$x_amount         = $_REQUEST['x_amount'];
$x_currency_code  = $_REQUEST['x_currency_code'];
$x_signature      = $_REQUEST['x_signature'];

$signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code);

$x_response     = $_REQUEST['x_response'];
$x_motivo       = $_REQUEST['x_response_reason_text'];
$x_id_invoice   = $_REQUEST['x_id_invoice'];
$x_autorizacion = $_REQUEST['x_approval_code'];

// Obtener invoice y valor esperado en el sistema del comercio
$numOrder = $x_id_invoice; // Este valor es un ejemplo, reemplázalo con el número de orden de tu sistema
$valueOrder = $x_amount;  // Reemplaza con el valor esperado de acuerdo a tu sistema

// Validar número de orden y valor
if ($x_id_invoice === $numOrder && $x_amount === $valueOrder) {
    // Validar la firma
    if ($x_signature == $signature) {
        // Manejar estados de la transacción
        $x_cod_response = $_REQUEST['x_cod_response'];
        switch ((int) $x_cod_response) {
            case 1:
                // Transacción aceptada
                mysqli_query($db, "INSERT INTO transacciones (id, estado, valor) VALUES ('$x_transaction_id', 'Aceptada', '$x_amount')");
                break;
            case 2:
                // Transacción rechazada
                mysqli_query($db, "INSERT INTO transacciones (id, estado, valor) VALUES ('$x_transaction_id', 'Rechazada', '$x_amount')");
                break;
            case 3:
                // Transacción pendiente
                mysqli_query($db, "INSERT INTO transacciones (id, estado, valor) VALUES ('$x_transaction_id', 'Pendiente', '$x_amount')");
                break;
            case 4:
                // Transacción fallida
                mysqli_query($db, "INSERT INTO transacciones (id, estado, valor) VALUES ('$x_transaction_id', 'fallida', '$x_amount')");
                break;
        }
        // Responder a ePayco con un código 200 OK
        http_response_code(200);
        echo "OK";
    } else {
        // Firma no válida
        http_response_code(400);
        error_log("Firma no válida para la transacción ID: $x_transaction_id");
    }
} else {
    // Orden o valor no coinciden
    http_response_code(400);
    error_log("Número de orden o valor no coinciden para la transacción ID: $x_transaction_id");
}
