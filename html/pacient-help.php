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
            Para acceder a la documentación deberá hacer clic en el botón de "Ver especialidades" de la categoría correspondiente al documento que esté buscando, luego de esto se le mostrará un listado de documentos disponibles para esa categoría, para ver el documento deberá hacer clic en el botón "Ver documento" y se le abrirá una lista de las especialidades de dicha categoría, seleccione la especialidad correspondiente y esto lo mandará a la página donde entrará a los documentos. Al ingresar, lo siguiente que debe hacer es seleccionar el documento que desea ver, haga clic en el botón "Ver documento" y esto lo mandará a la página donde se le mostrará el documento; al apretar el botón de leer, ya estará dentro del documento.
          </li>
          <video
            class="paciente-help-video"
            src="../video/tutorial-acceso-documentos.mp4"
            controls
            controlsList="nodownload"
            oncontextmenu="return false;"
          ></video>
          <li>
            Para acceder a la sección de encuestas, deberá hacer clic en el botón "Encuestas" en el menú principal. Esto lo llevará a la página principal del área, donde podrá ver los cuestionarios disponibles y participar en ellos. Para realizar uno, seleccione la opción deseada y siga las instrucciones proporcionadas.
          </li>
        </ul>
      </main>
    </div>
  </body>
</html>

