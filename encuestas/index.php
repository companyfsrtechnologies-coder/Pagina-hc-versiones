<?php

require_once("../html/conect-bd.php");

$sqlEncuestas = "SELECT * FROM encuesta;";

$resultado = $conn->execute_query($sqlEncuestas);

$datosEncuestas = $resultado->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas del hospital</title>
</head>

<body>
    <p>Sírvase responder la encuesta que le corresponde y ninguna otra por favor.</p>
    <ul>
        <?php foreach ($datosEncuestas as $encuesta): ?>
            <li><?= $encuesta['descripcion'] ?></li>
        <?php endforeach; ?>
    </ul>

    <p>
        <a href="crear-encuesta.php">Crear encuesta</a>
    </p>
</body>

</html>