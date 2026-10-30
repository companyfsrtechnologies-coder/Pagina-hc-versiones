<?php

require_once(__DIR__ . '/../html/conect-bd.php');
require_once("SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conexion);
$sistema->cargarEncuestas();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Encuestas del hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/paciente-estilos.css">
    <link rel="stylesheet" href="../css/botones-estilos.css">
</head>

<body class="body-docsconsulta">
    <div class="universal-container-pacient-dc encuestas-container">
        <nav class="navbar navbar-dark">
            <div class="container-fluid">
                <section class="pantallas-secundarias">
                    <span class="eyebrow">Participación del paciente</span>
                    <h1>Encuestas del hospital</h1>
                    <p>Tu opinión nos ayuda a mejorar la atención.</p>
                </section>
            </div>
        </nav>

        <header class="header-docsconsulta encuestas-header">
            <div class="cabecera-texto">
                <div class="titulo-hospital">HOSPITAL DE CLÍNICAS</div>
                <div class="subtitulo-hospital">Dr. Manuel Quintela</div>
            </div>
            <div class="encuestas-header-actions">
                <a href="../html/pacient-pp.php" class="btn btn-primary btn-help">Documentos</a>
                <a href="../index.php" class="btn btn-primary btn-help">Inicio</a>
            </div>
        </header>

        <main class="encuestas-content">
            <div class="encuestas-intro">
                <span class="encuestas-kicker">Disponibles ahora</span>
                <h2>Elegí una encuesta</h2>
                <p>Seleccioná una opción para compartir tu experiencia con el hospital.</p>
            </div>

            <?php if ($sistema->getEncuestas()): ?>
                <div class="encuestas-grid">
                    <?php foreach ($sistema->getEncuestas() as $encuesta): ?>
                        <article class="encuesta-card">
                            <div class="encuesta-card-icon" aria-hidden="true">?</div>
                            <div class="encuesta-card-body">
                                <h3><?= htmlspecialchars($encuesta->getTitulo()) ?></h3>
                                <p><?= htmlspecialchars($encuesta->getDescripcion()) ?></p>
                                <a class="btn btn-primary encuesta-card-action"
                                   href="responder.php?id=<?= (int) $encuesta->getId() ?>">
                                    Responder encuesta
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="encuestas-empty">
                    <h2>No hay encuestas disponibles</h2>
                    <p>En este momento no hay encuestas para responder.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>
