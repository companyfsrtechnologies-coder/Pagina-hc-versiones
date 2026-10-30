<?php
require_once('conect-bd.php');

$resultado = $conn->query("SELECT id, contraceña FROM funcionafio");

$stmt = $conn->prepare(
    "UPDATE funcionafio SET contraceña = ? WHERE id = ?"
);

$migradas = 0;
$omitidas = 0;

while ($usuario = $resultado->fetch_assoc()) {
    // Evita aplicar un hash sobre otro hash si se abre este archivo otra vez.
    if (password_get_info($usuario['contraceña'])['algo'] !== null) {
        $omitidas++;
        continue;
    }

    $hash = password_hash($usuario['contraceña'], PASSWORD_DEFAULT);

    $stmt->bind_param("si", $hash, $usuario['id']);
    $stmt->execute();
    $migradas++;
}

echo "Contraseñas migradas correctamente.";

