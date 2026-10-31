<?php

require_once(__DIR__ . "/../html/conect-bd.php");
require_once("SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conexion);
$sistema->cargarEncuestas(false);

$idEncuesta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$encuesta = $idEncuesta !== false && $idEncuesta !== null
    ? $sistema->getEncuesta($idEncuesta)
    : null;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados<?= $encuesta ? ' - ' . htmlspecialchars($encuesta->getTitulo(), ENT_QUOTES, 'UTF-8') : '' ?></title>
    <link rel="stylesheet" href="../css/paciente-estilos.css">
    <link rel="stylesheet" href="../css/botones-estilos.css">
</head>

<body class="body-docsconsulta">
    <div class="universal-container-pacient-dc encuestas-container resultados-container">
        <nav class="navbar navbar-dark">
            <div class="container-fluid">
                <section class="pantallas-secundarias">
                    <span class="eyebrow">Gestión de encuestas</span>
                    <h1>Resultados de la encuesta</h1>
                    <p>Consultá las respuestas registradas y la experiencia compartida por los pacientes.</p>
                </section>
            </div>
        </nav>

        <header class="header-docsconsulta encuestas-header">
            <div class="cabecera-texto">
                <div class="titulo-hospital">HOSPITAL DE CLÍNICAS</div>
                <div class="subtitulo-hospital">Dr. Manuel Quintela</div>
            </div>
            <a class="btn btn-primary" href="../html/admin-menu.php">Volver al menú</a>
        </header>

        <main class="resultados-content">
            <?php if (!$encuesta): ?>
                <section class="resultados-empty" role="alert">
                    <span class="encuestas-kicker">Encuesta no disponible</span>
                    <h2>No se encontraron resultados</h2>
                    <p>La encuesta solicitada no existe o ya no está disponible.</p>
                    <a class="btn btn-primary" href="../html/admin-menu.php">Volver al menú</a>
                </section>
            <?php else: ?>
                <section class="resultados-intro">
                    <span class="encuestas-kicker">Resumen de respuestas</span>
                    <h2><?= htmlspecialchars($encuesta->getTitulo(), ENT_QUOTES, 'UTF-8') ?></h2>
                    <p><?= htmlspecialchars($encuesta->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
                </section>

                <div class="resultados-table-wrap">
                    <table class="resultados-table">
                        <caption class="sr-only">Resultados por pregunta</caption>
                        <thead>
                            <tr>
                                <th scope="col">Pregunta</th>
                                <th scope="col">Tipo de respuesta</th>
                                <th scope="col">Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($encuesta->getPreguntas() as $pregunta): ?>
                                <tr>
                                    <th scope="row"><?= htmlspecialchars($pregunta->getTexto(), ENT_QUOTES, 'UTF-8') ?></th>
                                    <td>
                                        <span class="resultado-tipo">
                                            <?= htmlspecialchars($pregunta->getTipo(), ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td class="resultado-valor">
                                        <?= $pregunta->getRespuesta()->mostrarResultado($sistema->obtenerValores($pregunta)) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>
