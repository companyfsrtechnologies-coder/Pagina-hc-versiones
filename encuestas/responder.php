<?php

require_once(__DIR__ . "/../html/conect-bd.php");
require_once("SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conexion);
$sistema->cargarEncuestas();

$encuesta = $sistema->getEncuesta($_GET['id']);
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = filter_var($_POST['cedula'] ?? '', FILTER_VALIDATE_INT);
    $idPaciente = null;

    if ($cedula !== false) {
        $consultaPaciente = $conn->prepare(
            'SELECT id FROM paciente WHERE cedula = ? LIMIT 1'
        );
        $consultaPaciente->bind_param('i', $cedula);
        $consultaPaciente->execute();
        $idPaciente = $consultaPaciente->get_result()->fetch_assoc()['id'] ?? null;
    }

    if (!$idPaciente) {
        $mensaje = "No se encontró un paciente con esa cédula.";
    } elseif ($sistema->yaRespondio($encuesta, $idPaciente)) {
        $mensaje = "Ese paciente ya respondió esta encuesta.";
    } else {
        // Cada respuesta recibe el valor que eligió el usuario
        foreach ($encuesta->getPreguntas() as $pregunta) {
            $valor = $_POST['respuestas'][$pregunta->getId()];
            $pregunta->getRespuesta()->setValor($valor);
        }

        try {
            $sistema->guardarRespuestas($encuesta, $idPaciente);
            header("Location: gracias.php");
            exit;
        } catch (RuntimeException $error) {
            $mensaje = $error->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($encuesta->getTitulo()) ?></title>
    <link rel="stylesheet" href="../css/paciente-estilos.css">
    <link rel="stylesheet" href="../css/botones-estilos.css">
</head>

<body class="body-docsconsulta">
    <div class="universal-container-pacient-dc encuesta-respuesta-container">
        <nav class="navbar navbar-dark">
            <div class="container-fluid">
                <section class="pantallas-secundarias">
                    <span class="eyebrow">Participación del paciente</span>
                    <h1>Responder encuesta</h1>
                    <p>Completá tus respuestas para ayudarnos a mejorar.</p>
                </section>
            </div>
        </nav>

        <header class="header-docsconsulta encuestas-header">
            <div class="cabecera-texto">
                <div class="titulo-hospital">HOSPITAL DE CLÍNICAS</div>
                <div class="subtitulo-hospital">Dr. Manuel Quintela</div>
            </div>
            <a href="index.php" class="btn btn-primary btn-help">Volver a encuestas</a>
        </header>

        <main class="encuesta-respuesta-content">
            <section class="encuesta-respuesta-intro">
                <span class="encuestas-kicker">Encuesta seleccionada</span>
                <h2><?= htmlspecialchars($encuesta->getTitulo()) ?></h2>
                <p><?= htmlspecialchars($encuesta->getDescripcion()) ?></p>
            </section>

            <?php if ($mensaje !== ""): ?>
                <div class="encuesta-alert" role="alert">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="encuesta-form">
                <div class="encuesta-identificacion">
                    <label for="cedula">Cédula del paciente</label>
                    <input type="number" id="cedula" name="cedula" min="1" inputmode="numeric" required>
                    <small>Usaremos este dato para registrar tu participación.</small>
                </div>

                <?php foreach ($encuesta->getPreguntas() as $numero => $pregunta): ?>
                    <fieldset class="pregunta-card">
                        <legend>
                            <span class="pregunta-numero"><?= $numero + 1 ?></span>
                            <?= htmlspecialchars($pregunta->getTexto()) ?>
                        </legend>
                        <div class="respuesta-control">
                            <?= $pregunta->getRespuesta()->mostrarHtml("respuestas[" . $pregunta->getId() . "]") ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>

                <button class="btn btn-primary encuesta-submit" type="submit">
                    Enviar respuestas
                </button>
            </form>
        </main>
    </div>
</body>

</html>
