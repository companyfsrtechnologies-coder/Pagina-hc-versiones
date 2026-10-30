<?php

require_once('conect-bd.php');

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id']) || !is_string($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('ID de encuesta inválido.');
}

$idEncuesta = (int) $_GET['id'];
$conn->execute_query(
    "DELETE FROM encuesta WHERE id_encuesta = ?",
    [$idEncuesta]
);

header("Location: admin-menu.php");
exit;
