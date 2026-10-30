<?php

require_once('conect-bd-xampp.php');

if ($_SERVER["REQUEST_METHOD"] == 'GET' && ($_GET['accion'] ?? '') === 'eliminar_usuario' && isset($_GET['id'])) {
    $idUsuario = $_GET['id'];
    $sqlUsuario = "DELETE FROM funcionafio WHERE id = ?";
    $conn->execute_query($sqlUsuario, [$idUsuario]);
    header("Location: admin-menu.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == 'GET' && ($_GET['accion'] ?? '') === 'eliminar_paciente' && isset($_GET['id'])) {
    $idPaciente = (int) $_GET['id'];
    $conn->begin_transaction();

    $eliminacionExitosa = $conn->execute_query(
        "DELETE FROM partisipacion WHERE id_paciente = ?",
        [$idPaciente]
    ) !== false;
    $eliminacionExitosa = $conn->execute_query(
        "DELETE FROM paciente WHERE id = ?",
        [$idPaciente]
    ) !== false && $eliminacionExitosa;
    $eliminacionExitosa = $conn->execute_query(
        "DELETE FROM usuario WHERE id = ?",
        [$idPaciente]
    ) !== false && $eliminacionExitosa;

    if (!$eliminacionExitosa) {
        $conn->rollback();
        http_response_code(500);
        exit('No se pudo eliminar el paciente.');
    }

    $conn->commit();
    header("Location: admin-menu.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == 'GET' && ($_GET['accion'] ?? '') === 'eliminar_encuesta' && isset($_GET['id'])) {
    $idEncuesta = (int) $_GET['id'];
    $conn->begin_transaction();

    $eliminacionExitosa = $conn->execute_query(
        "DELETE r
         FROM respuesta r
         INNER JOIN pregunta p ON p.id_pregunta = r.id_pregunta
         WHERE p.id_encuesta = ?",
        [$idEncuesta]
    ) !== false;
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
}
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    if (isset($_GET['id_pagina'])) {
        $idPagina = (int) $_GET['id_pagina'];
        $conn->execute_query(
            "DELETE FROM paginas_doc WHERE id_pagina = ?",
            [$idPagina]
        );
        header("Location: enfermero-menu.php");
        exit;
    }

    if (isset($_GET['id_docuemnto'])) {
        $idDocumento = $_GET['id_docuemnto'];
        $sqlDocumento = "DELETE FROM documento WHERE id_docuemnto = ?";
        $resultadoDocumento = $conn->execute_query($sqlDocumento, [$idDocumento]);
        header("Location: enfermero-menu.php");
        exit;
    }
}

