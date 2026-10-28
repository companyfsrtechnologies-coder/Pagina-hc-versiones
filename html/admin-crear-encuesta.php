<?php

session_start();

require_once("conect-bd.php");
require_once("../encuestas/SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $datosPreguntas = $_POST['preguntas'] ?? [];

    if ($titulo === '' || $descripcion === '' || empty($datosPreguntas)) {
        header("Location: admin-crear-encuesta.php");
        exit;
    }

    $encuesta = new Encuesta(null, $titulo, $descripcion);

    foreach ($datosPreguntas as $datosPregunta) {
        $textoPregunta = trim($datosPregunta['texto'] ?? '');
        $tipo = $datosPregunta['tipo'] ?? 'texto';

        if ($textoPregunta === '') {
            continue;
        }

        $respuesta = $sistema->crearRespuesta($tipo);
        if ($respuesta === null) {
            $respuesta = $sistema->crearRespuesta('texto');
        }

        $pregunta = new Pregunta(null, $textoPregunta, $respuesta);
        $encuesta->agregarPregunta($pregunta);
    }

    $idAdmin = isset($_SESSION['id_usuario']) ? (int) $_SESSION['id_usuario'] : 0;

    if ($idAdmin <= 0) {
        header("Location: admin-ingreso.php");
        exit;
    }

    $sistema->guardarEncuesta($encuesta, $idAdmin);

    header("Location: admin-menu.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Creador de encuestas</title>

    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 30px;
      }

      .contenedor {
        max-width: 800px;
        margin: auto;
        background-color: white;
        padding: 25px;
        border-radius: 10px;
      }

      input,
      select,
      textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        box-sizing: border-box;
      }

      textarea {
        min-height: 100px;
        resize: vertical;
        font-family: Arial, sans-serif;
      }

      .pregunta {
        border: 1px solid #ccc;
        padding: 20px;
        margin-top: 20px;
        border-radius: 8px;
      }

      button {
        padding: 10px 15px;
        margin-top: 10px;
        cursor: pointer;
      }

      .agregar {
        background-color: #0d6efd;
        color: white;
        border: none;
      }

      .publicar {
        background-color: #198754;
        color: white;
        border: none;
      }

      .eliminar {
        background-color: #dc3545;
        color: white;
        border: none;
      }
    </style>
    <link rel="stylesheet" href="../css/botones-estilos.css" />
  </head>

  <body>
    <div class="contenedor">
      <h1>Crear encuesta</h1>

      <form id="encuestaForm" method="POST">
        <label> Título de la encuesta </label>

        <input
          type="text"
          id="titulo"
          name="titulo"
          placeholder="Ej: Encuesta de satisfacción"
          required
        />

        <label> Descripción de la encuesta </label>

        <textarea
          id="descripcion"
          name="descripcion"
          placeholder="Describe el propósito de la encuesta"
          required
        ></textarea>

        <div id="preguntas"></div>
        <div id="preguntasFormulario"></div>

        <button type="button" class="agregar" onclick="agregarPregunta()">
          + Agregar pregunta
        </button>

        <br />

        <button type="button" class="publicar" onclick="crearEncuesta()">
          Crear encuesta
        </button>
      </form>
    </div>

    <script>
      let numeroPregunta = 0;

      function agregarPregunta() {
        numeroPregunta++;

        const preguntas = document.getElementById("preguntas");

        const pregunta = document.createElement("div");

        pregunta.classList.add("pregunta");

        pregunta.innerHTML = `

                <h3>Pregunta ${numeroPregunta}</h3>

                <label>
                    Pregunta:
                </label>

                <input
                    type="text"
                    class="textoPregunta"
                    placeholder="Escribí la pregunta">


                <label>
                    Tipo de respuesta:
                </label>

                <select
                    class="tipoRespuesta"
                    onchange="cambiarTipo(this)">

                    <option value="texto">
                        Respuesta de texto
                    </option>

                    <option value="binaria">
                        Sí / No
                    </option>

                    <option value="escala">
                        Escala de 1 a 10
                    </option>

                </select>


                <div class="opciones">

                </div>


                <button
                    class="eliminar"
                    onclick="this.parentElement.remove()">

                    Eliminar pregunta

                </button>

            `;

        preguntas.appendChild(pregunta);
      }

      function cambiarTipo(select) {
        const pregunta = select.parentElement;

        const opciones = pregunta.querySelector(".opciones");

        if (select.value === "multiple" || select.value === "unica") {
          opciones.innerHTML = `

                    <label>
                        Opciones:
                    </label>

                    <input
                        type="text"
                        placeholder="Ej: Excelente, Buena, Regular">

                `;
        } else {
          opciones.innerHTML = "";
        }
      }

      function crearEncuesta() {
        const titulo = document.getElementById("titulo").value.trim();
        const descripcion = document.getElementById("descripcion").value.trim();

        if (titulo === "") {
          alert("Escribí un título para la encuesta.");
          document.getElementById("titulo").focus();
          return;
        }

        if (descripcion === "") {
          alert("Escribí una descripción para la encuesta.");
          document.getElementById("descripcion").focus();
          return;
        }

        const preguntas = document.querySelectorAll(".pregunta");

        if (preguntas.length === 0) {
          alert("Agregá al menos una pregunta.");
          return;
        }

        const preguntasFormulario = document.getElementById("preguntasFormulario");
        preguntasFormulario.innerHTML = "";

        for (let i = 0; i < preguntas.length; i++) {
          const texto = preguntas[i].querySelector(".textoPregunta").value.trim();
          const tipo = preguntas[i].querySelector(".tipoRespuesta").value;

          if (texto === "") {
            alert("Completá el texto de todas las preguntas.");
            preguntas[i].querySelector(".textoPregunta").focus();
            return;
          }

          preguntasFormulario.insertAdjacentHTML("beforeend", `
            <input type="hidden" name="preguntas[${i}][texto]" value="${escapeHtml(texto)}">
            <input type="hidden" name="preguntas[${i}][tipo]" value="${tipo}">
          `);
        }

        document.getElementById("encuestaForm").submit();

      }

      function escapeHtml(texto) {
        const elemento = document.createElement("div");
        elemento.textContent = texto;
        return elemento.innerHTML;
      }
    </script>
  </body>
</html>
