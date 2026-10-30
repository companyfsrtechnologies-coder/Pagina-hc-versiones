<?php

mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$usuario = getenv('DB_USER') ?: 'root';
$contrasena = getenv('DB_PASSWORD') ?: '';
$base_datos = getenv('DB_NAME') ?: 'sigsm_hospital_corregida';
$puerto = (int) (getenv('DB_PORT') ?: 3306);

$conn = new mysqli($host, $usuario, $contrasena, $base_datos, $puerto);

if ($conn->connect_errno) {
    http_response_code(500);
    die(
        'No se pudo conectar con la base de datos. '
        . 'Verificá DB_HOST, DB_PORT, DB_NAME, DB_USER y DB_PASSWORD.'
    );
}

if (!$conn->set_charset('utf8mb4')) {
    http_response_code(500);
    die('No se pudo configurar la codificación de la conexión a la base de datos.');
}

// Las encuestas históricas usan este nombre de variable.
$conexion = $conn;
