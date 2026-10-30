<?php

require_once("conexion.php");
require_once("SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conexion);
$sistema->cargarEncuestas();

$encuesta = $sistema->getEncuesta($_GET['id']);
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
<<<<<<< HEAD
    $cedula = $_POST['cedula'];

    if ($sistema->yaRespondio($encuesta, $cedula)) {
        $mensaje = "Esa cédula ya respondió esta encuesta.";
=======
    $email = $_POST['email'];

    if ($sistema->yaRespondio($encuesta, $email)) {
        $mensaje = "Ese email ya respondió esta encuesta.";
>>>>>>> 237a3831ac6384806821db232ba47c3f3503fc89
    } else {
        // Cada respuesta recibe el valor que eligió el usuario
        foreach ($encuesta->getPreguntas() as $pregunta) {
            $valor = $_POST['respuestas'][$pregunta->getId()];
            $pregunta->getRespuesta()->setValor($valor);
        }

        $sistema->guardarRespuestas($encuesta, $email);

        header("Location: gracias.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $encuesta->getTitulo() ?></title>
</head>

<body>
    <h1><?= $encuesta->getTitulo() ?></h1>
    <p><?= $encuesta->getDescripcion() ?></p>

    <p style="color: red"><?= $mensaje ?></p>

    <form method="POST">
        <p>
            <label>Tu email</label><br>
            <input type="email" name="email" required>
        </p>

        <?php foreach ($encuesta->getPreguntas() as $pregunta): ?>
            <fieldset>
                <legend><?= $pregunta->getTexto() ?></legend>

                <!-- Cada tipo de respuesta sabe dibujar su propio campo -->
                <?= $pregunta->getRespuesta()->mostrarHtml("respuestas[" . $pregunta->getId() . "]") ?>
            </fieldset>
        <?php endforeach; ?>

        <p><input type="submit" value="Enviar"></p>
    </form>

    <p><a href="index.php">Volver</a></p>
</body>

</html>
