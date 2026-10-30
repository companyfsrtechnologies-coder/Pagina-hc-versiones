<?php

require_once('conect-bd-xampp.php');

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id']) || !is_string($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('ID de encuesta inválido.');
}

$idEncuesta = (int) $_GET['id'];

$conn->begin_transaction();

$eliminacionExitosa = true;
$eliminacionExitosa = $conn->execute_query(
    "DELETE r
     FROM respuesta r
     INNER JOIN pregunta p ON p.id_pregunta = r.id_pregunta
     WHERE p.id_encuesta = ?",
    [$idEncuesta]
) !== false && $eliminacionExitosa;

$eliminacionExitosa = $conn->execute_query(
    "DELETE FROM partisipacion WHERE id_encuesta = ?",
    [$idEncuesta]
) !== false && $eliminacionExitosa;

$eliminacionExitosa = $conn->execute_query(
    "DELETE FROM pregunta WHERE id_encuesta = ?",
    [$idEncuesta]
) !== false && $eliminacionExitosa;

$eliminacionExitosa = $conn->execute_query(
    "DELETE FROM encuesta WHERE id_encuesta = ?",
    [$idEncuesta]
) !== false && $eliminacionExitosa;

if (!$eliminacionExitosa) {
    $conn->rollback();
    http_response_code(500);
    exit('No se pudo eliminar la encuesta.');
}

$conn->commit();

header("Location: admin-menu.php");
exit;

