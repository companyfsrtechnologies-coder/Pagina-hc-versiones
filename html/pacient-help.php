<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ayuda para el usuario</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="../css/paciente-estilos.css" />
    <link rel="stylesheet" href="../css/botones-estilos.css" />
  </head>
  <body class="body-docsconsulta">
    <div class="paciente-page-shell">
      <header class="header-docsconsulta">
        <div class="cabecera-texto">
          <div class="titulo-hospital">HOSPITAL DE CLÍNICAS</div>
          <div class="subtitulo-hospital">Centro de atención al paciente</div>
        </div>
        <a href="pacient-pp.php" class="btn btn-primary btn-help">Volver</a>
      </header>

      <main class="paciente-page-body">
        <h1>Ayuda</h1>
        <p>
          Bienvenido a la sección de ayuda. Aquí encontrarás información útil para
          navegar y utilizar nuestro portal de pacientes.
        </p>

        <ul class="paciente-help-list">
          <li>
            para aceder a la documentación debera acer clic en el boton de "ver especilidades" de la categoria corespodiente 
            al documento que este buscando, luego de esto se le mostrara un listado de documentos disponibles para esa categoria, 
            para ver el documento debera acer clic en el boton "ver documento" 
            y se le abrira una lista de las especialidades de dicha categoría, 
            seleccione la especialidad correspondiente y esto lo mandara a la página donde entrara los documentos. 
            Al ingresar lo suigiente que debe haceer es seleccionar el documento que desea ver, hacer clic en el boton "ver documento" 
            y esto lo mandara a la página donde se le mostrara el documento apretar el botón de leer ya estara dentro del documento.
          </li>
          <video
            class="paciente-help-video"
            src="../video/tutorial-acceso-documentos.mp4"
            controls
            controlsList="nodownload"
            oncontextmenu="return false;"
          ></video>
          <li>
            Para acceder a la sección de encuestas, deberá hacer clic en el botón "Encuestas" en el menú principal.
            Esto lo llevará a la página de encuestas, donde podrá ver las encuestas disponibles y participar en ellas. Para participar, seleccione la encuesta deseada y siga las instrucciones proporcionadas.
          </li>
        </ul>
      </main>
    </div>
  </body>
</html>
