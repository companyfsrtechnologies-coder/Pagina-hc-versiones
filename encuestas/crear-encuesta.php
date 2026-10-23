<?php
require_once('../html/conect-bd.php');


if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    if (!empty($_POST['titulo-encuesta']) && !empty($_POST['descripcion'])) {

        $titulo = $_POST['titulo-encuesta'];
        $descripcion = $_POST['descripcion'];
        $adminid = 1; // ID del administrador que crea la encuesta, se puede cambiar según la lógica de autenticación

        // La tabla encuesta no tiene columna categoria, por eso no se guarda
        $sqlInsert = "INSERT INTO encuesta (titulo, descripcion, id_admin)
        VALUES (?, ?, ?)";

        $conn->execute_query($sqlInsert, [$titulo, $descripcion, $adminid]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear encuesta</title>
</head>

<body>


    <form method="POST">

        <div>
            <label for="titulo-encuesta">Título</label>
            <input type="text" id="titulo-encuesta" name="titulo-encuesta" required>
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" required>
        </div>



        <a href="crearPregunta.php">Crear pregunta</a>

        <input type="submit" value="Enviar">
    </form>

</body>

</html>