<?php

require_once(__DIR__ . "/../html/conect-bd.php");
require_once("SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conexion);
$sistema->cargarEncuestas();

$encuesta = $sistema->getEncuesta($_GET['id']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados - <?= $encuesta->getTitulo() ?></title>
</head>

<body>
    <h1>Resultados: <?= $encuesta->getTitulo() ?></h1>

    <table border="1">
        <tr>
            <th>Pregunta</th>
            <th>Tipo</th>
            <th>Resultado</th>
        </tr>

        <?php foreach ($encuesta->getPreguntas() as $pregunta): ?>
            <tr>
                <td><?= $pregunta->getTexto() ?></td>
                <td><?= $pregunta->getTipo() ?></td>
                <!-- Cada tipo de respuesta sabe calcular su propio resultado -->
                <td><?= $pregunta->getRespuesta()->mostrarResultado($sistema->obtenerValores($pregunta)) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="index.php">Volver</a></p>
</body>

</html>
