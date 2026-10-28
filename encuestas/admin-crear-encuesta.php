<?php
require_once('../html/conect-bd.php');


if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    if (!empty($_POST['titulo-encuesta']) && !empty($_POST['descripcion'])) {

        $titulo = $_POST['titulo-encuesta'];
        $descripcion = $_POST['descripcion'];
        $adminid = 1; // ID del administrador que crea la encuesta, se puede cambiar según la lógica de autenticación

        // La tabla encuesta no tiene columna categoria, por eso no se guarda
        $sqlInsert = "INSERT INTO encuesta (titulo, descripcion, id_admin, fecha_creacion)
        VALUES (?, ?, ?, CURRENT_DATE)";

        $conn->execute_query($sqlInsert, [$titulo, $descripcion, $adminid]);

        header("Location: admin-menu.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Creador de encuestas</title>

    <style>
      * {
        box-sizing: border-box;
      }

      body {
        --site-primary: #173d63;
        --site-primary-strong: #0e2d4d;
        --site-accent: #2a7acb;
        --site-text: #1f2937;
        --site-muted: #5d7288;
        --site-border: rgba(148, 163, 184, 0.28);
        --site-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        min-height: 100vh;
        margin: 0;
        padding: 0 0 48px;
        background: linear-gradient(180deg, #f2f8ff 0%, #eaf3ff 100%);
        color: var(--site-text);
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      .barra-superior {
        background: linear-gradient(135deg, #173d63 0%, #225d95 100%);
        box-shadow: 0 8px 24px rgba(23, 61, 99, 0.15);
      }

      .barra-contenido {
        width: min(1100px, 100%);
        min-height: 76px;
        margin: 0 auto;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
      }

      .marca {
        margin: 0;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.02em;
      }

      .volver {
        color: #fff;
        font-size: 0.92rem;
        font-weight: 600;
        text-decoration: none;
        padding: 9px 15px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 999px;
        transition: background 0.2s ease, border-color 0.2s ease;
      }

      .volver:hover,
      .volver:focus-visible {
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.7);
      }

      main {
        width: min(900px, calc(100% - 32px));
        margin: 42px auto 0;
      }

      .contenedor {
        padding: clamp(22px, 5vw, 42px);
        background: #fff;
        border: 1px solid var(--site-border);
        border-radius: 22px;
        box-shadow: var(--site-shadow);
      }

      .encabezado-formulario {
        margin-bottom: 30px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e8eef5;
      }

      .sobrelinea {
        margin: 0 0 8px;
        color: var(--site-accent);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
      }

      h1 {
        margin: 0;
        color: var(--site-primary);
        font-size: clamp(1.7rem, 4vw, 2.2rem);
        line-height: 1.2;
      }

      .introduccion {
        margin: 10px 0 0;
        color: var(--site-muted);
        line-height: 1.65;
      }

      .campo {
        margin-bottom: 22px;
      }

      label {
        display: block;
        margin-bottom: 8px;
        color: var(--site-primary-strong);
        font-size: 0.94rem;
        font-weight: 700;
      }

      input,
      select,
      textarea {
        display: block;
        width: 100%;
        min-height: 46px;
        padding: 11px 14px;
        border: 1px solid #d5e0eb;
        border-radius: 10px;
        background: #fbfdff;
        color: var(--site-text);
        font: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
      }

      textarea {
        min-height: 120px;
        resize: vertical;
      }

      input::placeholder,
      textarea::placeholder {
        color: #8798aa;
      }

      input:focus,
      select:focus,
      textarea:focus {
        outline: none;
        border-color: var(--site-accent);
        box-shadow: 0 0 0 3px rgba(42, 122, 203, 0.14);
      }

      #preguntas {
        display: grid;
        gap: 16px;
      }

      .pregunta {
        padding: 22px;
        border: 1px solid var(--site-border);
        border-radius: 16px;
        background: #f7fbff;
      }

      .pregunta h3 {
        margin: 0 0 20px;
        color: var(--site-primary);
        font-size: 1.08rem;
      }

      .pregunta .opciones {
        margin-top: 18px;
      }

      button {
        font-family: inherit;
      }

      .acciones .agregar {
        margin-top: 22px;
        background: #edf6ff !important;
        color: var(--site-primary) !important;
        box-shadow: none !important;
      }

      .acciones .agregar:hover:not(:disabled) {
        background: #dceeff !important;
        box-shadow: 0 8px 16px rgba(23, 61, 99, 0.1) !important;
      }

      .publicar {
        min-width: 190px;
      }

      .pregunta .eliminar {
        margin-top: 4px;
        background: #fff1f2 !important;
        color: #b42332 !important;
        box-shadow: none !important;
      }

      .pregunta .eliminar:hover:not(:disabled) {
        background: #ffe0e3 !important;
        box-shadow: 0 8px 16px rgba(180, 35, 50, 0.12) !important;
      }

      .acciones {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
        padding-top: 22px;
        border-top: 1px solid #e8eef5;
      }

      @media (max-width: 560px) {
        .barra-contenido {
          min-height: 68px;
          padding: 12px 16px;
        }

        main {
          width: min(100% - 24px, 900px);
          margin-top: 24px;
        }

        .pregunta {
          padding: 18px;
        }

        .acciones {
          align-items: stretch;
          flex-direction: column;
        }

        .acciones button {
          width: 100%;
        }
      }
    </style>
    <link rel="stylesheet" href="../css/botones-estilos.css" />
  </head>

  <body>
    <header class="barra-superior">
      <div class="barra-contenido">
        <p class="marca">Administración de encuestas</p>
        <a class="volver" href="../html/admin-menu.php">Volver al panel</a>
      </div>
    </header>

    <main>
      <form method="POST" action="">
        <div class="contenedor">
          <div class="encabezado-formulario">
            <p class="sobrelinea">Nueva encuesta</p>
            <h1>Crear encuesta</h1>
            <p class="introduccion">
              Completá los datos principales y agregá las preguntas que quieras incluir.
            </p>
          </div>

          <div class="campo">
            <label for="titulo-encuesta">Título de la encuesta</label>
            <input
              id="titulo-encuesta"
              type="text"
              name="titulo-encuesta"
              placeholder="Ej.: Encuesta de satisfacción"
              required
            />
          </div>

          <div class="campo">
            <label for="descripcion">Descripción</label>
            <textarea
              id="descripcion"
              name="descripcion"
              placeholder="Contá brevemente el objetivo de esta encuesta."
              required
            ></textarea>
          </div>

          <div id="preguntas"></div>

          <div class="acciones">
            <button class="agregar" type="button" onclick="agregarPregunta()">
              + Agregar pregunta
            </button>
            <button class="publicar" type="submit">Crear encuesta</button>
          </div>
        </div>
      </form>
    </main>

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

                    <option value="multiple">
                        Opción múltiple
                    </option>

                    <option value="binaria">
                        Respuesta binaria
                    </option>

                </select>


                <div class="opciones">

                </div>


                <button
                    class="eliminar"
                    type="button"
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
        const titulo = document.getElementById("titulo").value;

        if (titulo === "") {
          alert("Escribí un título para la encuesta.");

          return;
        }

        const preguntas = document.querySelectorAll(".pregunta");

        if (preguntas.length === 0) {
          alert("Agregá al menos una pregunta.");

          return;
        }

        alert("¡Encuesta creada correctamente!");
      }
    </script>
  </body>
</html>
