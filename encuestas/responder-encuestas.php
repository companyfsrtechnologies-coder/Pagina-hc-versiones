<?php

require_once("./db_encuestas.php");
require_once("./setup.php");
$encuesta = $encuesta1;

$tamanoListaPreguntas = sizeof($encuesta->getListaPreguntas());

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $idPregunta = $_POST["id"];
    $pregunta = $encuesta->getPregunta($idPregunta);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas</title>
</head>

<body>
    <h1>Encuestas</h1>

    <p>
        <?= $encuesta1->getTituloYDescripcion(); ?>
    </p>

    <p>Por favor, responda las <?= $tamanoListaPreguntas ?> preguntas de la encuesta a continuación.</p>

    <form method="POST">
        <?php foreach ($encuesta1->getListaPreguntas() as $pregunta): ?>

            <fieldset>
                <legend><?= $pregunta->getPregunta() ?></legend>

                <p><?= $pregunta->getTexto() ?></p>

                <input type="hidden" name="<?= $pregunta->getId() ?>">

                <label><input type="radio" name="respuestas[<?= $pregunta->getId() ?>]" value="si" required> Sí</label>
                <label><input type="radio" name="respuestas[<?= $pregunta->getId() ?>]" value="no"> No</label>
            </fieldset>

            <hr>
        <?php endforeach; ?>
        <input type="submit" value="Enviar">
    </form>

</body>

</html>