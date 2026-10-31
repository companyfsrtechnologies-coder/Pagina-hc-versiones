<?php

session_start();

require_once("conect-bd-xampp.php");
require_once("../encuestas/SistemaEncuestas.php");

$sistema = new SistemaEncuestas($conn);
$idEncuesta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$encuestaEditar = null;
$errorCreacion = '';

if ($idEncuesta) {
    $sistema->cargarEncuestas(false);
    $encuestaEditar = $sistema->getEncuesta($idEncuesta);
    if (!$encuestaEditar) {
        header("Location: admin-menu.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEncuesta = filter_input(INPUT_POST, 'id_encuesta', FILTER_VALIDATE_INT);
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $datosPreguntas = $_POST['preguntas'] ?? [];

    if ($titulo === '' || $descripcion === '' || empty($datosPreguntas)) {
        $errorCreacion = 'Completá el título, la descripción y al menos una pregunta.';
    } else {
        $encuesta = new Encuesta($idEncuesta ?: null, $titulo, $descripcion);

        foreach ($datosPreguntas as $datosPregunta) {
            $textoPregunta = trim($datosPregunta['texto'] ?? '');
            $tipo = $datosPregunta['tipo'] ?? 'texto';
            $idPregunta = filter_var($datosPregunta['id'] ?? null, FILTER_VALIDATE_INT);

            if ($textoPregunta === '') {
                continue;
            }

            $respuesta = $sistema->crearRespuesta($tipo);
            if ($respuesta === null) {
                $respuesta = $sistema->crearRespuesta('texto');
            }

            $pregunta = new Pregunta($idPregunta ?: null, $textoPregunta, $respuesta);
            $encuesta->agregarPregunta($pregunta);
        }

        $idAdmin = isset($_SESSION['id_usuario']) ? (int) $_SESSION['id_usuario'] : 0;

        if ($idAdmin <= 0) {
            header("Location: admin-ingreso.php");
            exit;
        }

        try {
            if ($idEncuesta) {
                $sistema->actualizarEncuesta($encuesta);
            } else {
                $sistema->guardarEncuesta($encuesta, $idAdmin);
            }
            header("Location: admin-menu.php");
            exit;
        } catch (RuntimeException $error) {
            $errorCreacion = $error->getMessage();
            $encuestaEditar = $encuesta;
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?= $encuestaEditar ? 'Editar encuesta' : 'Crear encuesta' ?></title>

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

      body {
        padding: 24px 16px;
        background: linear-gradient(180deg, #edf6ff 0%, #f5f8fc 100%);
        color: #1f2937;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      .contenedor {
        width: min(860px, 100%);
        padding: 0;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.28);
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
      }

      .encuesta-hero {
        padding: 30px clamp(22px, 5vw, 46px);
        background: linear-gradient(135deg, #173d63, #1f5c8d);
        color: #fff;
      }

      .encuesta-kicker {
        display: block;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.76);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
      }

      h1 {
        margin: 0;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
      }

      .encuesta-hero p {
        margin: 10px 0 0;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.6;
      }

      .form-contenido {
        padding: clamp(24px, 5vw, 44px);
      }

      .campo-grupo {
        margin-bottom: 24px;
      }

      label {
        color: #173d63;
        font-weight: 700;
      }

      input,
      select,
      textarea {
        border: 1px solid #c9d9e8;
        border-radius: 10px;
        font: inherit;
      }

      input:focus,
      select:focus,
      textarea:focus {
        border-color: #2a7acb;
        outline: 3px solid rgba(42, 122, 203, 0.16);
      }

      .pregunta {
        border: 1px solid #dfeaf5;
        border-radius: 16px;
        background: #f8fbfe;
      }

      .pregunta h3 {
        margin-top: 0;
        color: #173d63;
        font-size: 1.1rem;
      }

      .pregunta-campos {
        display: grid;
        gap: 14px;
        grid-template-columns: minmax(0, 1fr) 220px;
      }

      .acciones-formulario {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid #dfeaf5;
      }

      .volver {
        align-self: center;
        color: #5d7288;
        font-weight: 700;
        text-decoration: none;
      }

      .alerta-error {
        margin: 24px 24px 0;
        padding: 13px 16px;
        border: 1px solid #f1b8bd;
        border-radius: 10px;
        background: #fff1f2;
        color: #842029;
      }

      @media (max-width: 640px) {
        body {
          padding: 12px 10px;
        }

        .pregunta-campos {
          grid-template-columns: 1fr;
        }

        .acciones-formulario {
          flex-direction: column-reverse;
        }

        .acciones-formulario button,
        .acciones-formulario a {
          width: 100%;
          text-align: center;
        }
      }
    </style>
    <link rel="stylesheet" href="../css/botones-estilos.css" />
  </head>

  <body>
    <div class="contenedor">
      <header class="encuesta-hero">
        <span class="encuesta-kicker">Gestión de encuestas</span>
        <h1><?= $encuestaEditar ? 'Editar encuesta' : 'Crear encuesta' ?></h1>
        <p><?= $encuestaEditar
          ? 'Actualizá el contenido para mantener la experiencia de los pacientes al día.'
          : 'Diseñá una encuesta clara para conocer mejor la experiencia de los pacientes.' ?></p>
      </header>

      <?php if (!empty($errorCreacion)): ?>
        <div class="alerta-error" role="alert">
          <?= htmlspecialchars($errorCreacion, ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php endif; ?>

      <form id="encuestaForm" class="form-contenido" method="POST">
        <?php if ($encuestaEditar): ?>
          <input type="hidden" name="id_encuesta" value="<?= (int) $encuestaEditar->getId(); ?>">
        <?php endif; ?>

        <div class="campo-grupo">
          <label for="titulo">Título de la encuesta</label>
          <input
            type="text"
            id="titulo"
            name="titulo"
            value="<?= htmlspecialchars($encuestaEditar ? $encuestaEditar->getTitulo() : '', ENT_QUOTES, 'UTF-8'); ?>"
            placeholder="Ej: Encuesta de satisfacción"
            required
          />
        </div>

        <div class="campo-grupo">
          <label for="descripcion">Descripción de la encuesta</label>
          <textarea
            id="descripcion"
            name="descripcion"
            placeholder="Describí el propósito de la encuesta"
            required
          ><?= htmlspecialchars($encuestaEditar ? $encuestaEditar->getDescripcion() : '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div id="preguntas"></div>
        <div id="preguntasFormulario"></div>

        <button type="button" class="agregar" onclick="agregarPregunta()">
          + Agregar pregunta
        </button>

        <div class="acciones-formulario">
          <a class="btn btn-primary volver" href="admin-menu.php">Cancelar y volver</a>
          <button type="button" class="publicar" onclick="crearEncuesta()">
            <?= $encuestaEditar ? 'Guardar cambios' : 'Crear encuesta' ?>
          </button>
        </div>
      </form>
    </div>

    <script>
      let numeroPregunta = 0;
      const preguntasIniciales = <?= json_encode(
        $encuestaEditar
          ? array_map(static function ($pregunta) {
              return [
                'id' => (int) $pregunta->getId(),
                'texto' => $pregunta->getTexto(),
                'tipo' => $pregunta->getTipo()
              ];
            }, $encuestaEditar->getPreguntas())
          : [],
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
      ); ?>;

      function agregarPregunta(datos = {}) {
        numeroPregunta++;

        const preguntas = document.getElementById("preguntas");
        const pregunta = document.createElement("div");

        pregunta.classList.add("pregunta");
        const textoInicial = datos.texto ? escapeHtml(datos.texto) : "";
        const tipoInicial = ["texto", "binaria", "escala"].includes(datos.tipo)
          ? datos.tipo
          : "texto";

        pregunta.innerHTML = `
          <h3>Pregunta ${numeroPregunta}</h3>
          <div class="pregunta-campos">
            <label>
              Texto de la pregunta
              <input type="text" class="textoPregunta" value="${textoInicial}"
                     placeholder="Escribí la pregunta">
            </label>
            <label>
              Tipo de respuesta
              <select class="tipoRespuesta">
                <option value="texto" ${tipoInicial === "texto" ? "selected" : ""}>Respuesta de texto</option>
                <option value="binaria" ${tipoInicial === "binaria" ? "selected" : ""}>Sí / No</option>
                <option value="escala" ${tipoInicial === "escala" ? "selected" : ""}>Escala de 1 a 5</option>
              </select>
            </label>
          </div>
          <button type="button" class="eliminar" onclick="this.parentElement.remove()">
            Eliminar pregunta
          </button>
        `;

        pregunta.dataset.id = datos.id || "";

        preguntas.appendChild(pregunta);
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
            ${preguntas[i].dataset.id
              ? `<input type="hidden" name="preguntas[${i}][id]" value="${preguntas[i].dataset.id}">`
              : ""}
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

      preguntasIniciales.forEach((pregunta) => agregarPregunta(pregunta));
    </script>
  </body>
</html>
