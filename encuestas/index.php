<?php

require_once("./Encuesta.php");
require_once("./Pregunta.php");

$encuesta1 = new Encuesta("Ejemplo de encuesta", "este ejemplo de encuesta servira para mostrar como funciona", "ejemplo");
$pregunta1 = new Pregunta("Pregunta 1", "Esta satisfecho con el servicio si o no?");
$pregunta2 = new Pregunta("Pregunta 2", "en que aspectos cree que se puede mejorar el servicio?");
$pregunta3 = new Pregunta("Pregunta 3", "fue facil de usar el servicio?");
$pregunta4 = new Pregunta("Pregunta 4", "el personal fue amable?");
$encuesta1->agregarPregunta($pregunta1);
$encuesta1->agregarPregunta($pregunta2);
$encuesta1->agregarPregunta($pregunta3);
$encuesta1->agregarPregunta($pregunta4);

$tamanoListaPreguntas = sizeof($encuesta1->getListaPreguntas());
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


    <p>Cuantas preguntas hay en la lista de preguntas de la encuesta 1</p>
    <p><?= $tamanoListaPreguntas; ?></p>

</body>

</html>