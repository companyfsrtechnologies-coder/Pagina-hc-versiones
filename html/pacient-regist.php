<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acceso del paciente</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="../css/paciente-estilos.css" />
  </head>

  <body class="body-docsconsulta">
    <main class="paciente-login-shell">
      <header class="paciente-login-header">
        <h1>Acceso del paciente</h1>
        <p>Ingresa tus datos para acceder al sistema.</p>
      </header>

      <div class="paciente-login-form">
        <form id="formAcceso" method="POST" action="../html/pacient-encuest.html">
          <div class="paciente-field">
            <label for="cedula">Cédula</label>
            <input
              type="text"
              id="cedula"
              name="cedula"
              placeholder="Ej: 12345678"
              required
            />
          </div>

          <div class="paciente-field">
            <label for="fecha">Fecha de nacimiento</label>
            <input type="date" id="fecha" name="fecha" required />
          </div>

          <button type="submit" class="paciente-btn" style="width: 100%;">Ingresar</button>
        </form>

        <div class="paciente-actions">
          <a href="../index.php" class="paciente-btn-secondary">Volver al inicio</a>
          <a href="../html/pacient-pp.php" class="paciente-btn-secondary">Ver documentos</a>
        </div>
      </div>
    </main>

    <script>
      const formulario = document.getElementById("formAcceso");

      formulario.addEventListener("submit", function (event) {
        event.preventDefault();

        const cedula = document.getElementById("cedula").value;
        const fecha = document.getElementById("fecha").value;

        if (cedula !== "" && fecha !== "") {
          window.location.href = "../html/pacient-encuest.html";
        } else {
          alert("Debes completar todos los campos.");
        }
      });
    </script>
  </body>
</html>
