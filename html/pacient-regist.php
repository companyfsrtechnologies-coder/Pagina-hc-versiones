<?php

session_start();
require_once('conect-bd-xampp.php');

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $fechaIngreso = trim($_POST['fecha'] ?? '');

    $consulta = $conn->prepare(
        'SELECT id, cedula, fecha_ingeso
         FROM paciente
         WHERE cedula = ? AND fecha_ingeso = ?
         LIMIT 1'
    );

    if ($consulta === false) {
        http_response_code(500);
        die('No se pudo preparar la consulta de autenticación.');
    }

    $consulta->bind_param('ss', $cedula, $fechaIngreso);

    if (!$consulta->execute()) {
        http_response_code(500);
        die('No se pudo ejecutar la consulta de autenticación.');
    }

    $resultadoUsuario = $consulta->get_result()->fetch_assoc();

    if ($resultadoUsuario) {
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = (int) $resultadoUsuario['id'];
        $_SESSION['rol'] = 'Paciente';
        header("Location: ../encuestas/index.php");
        exit;
    } else {
        $error = "La cédula o la fecha de ingreso no son correctas.";
    }
}
?>

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
    <link rel="stylesheet" href="../css/botones-estilos.css" />
  </head>

  <body class="body-docsconsulta">
    <main class="paciente-login-shell">
      <header class="paciente-login-header">
        <h1>Acceso del paciente</h1>
        <p>Ingresa tus datos para acceder al sistema.</p>
      </header>

      <div class="paciente-login-form">
        <form id="formAcceso" method="POST" action="pacient-regist.php">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
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
            <label for="fecha">Fecha de ingreso</label>
            <input type="date" id="fecha" name="fecha" required />
          </div>

          <button type="submit" class="paciente-btn" style="width: 100%;">Ingresar</button>
        </form>

        <div class="paciente-actions">
          <a href="../index.php" class="paciente-btn-secondary">Volver al inicio</a>
          <a href="pacient-pp.php" class="paciente-btn-secondary">Ver documentos</a>
        </div>
      </div>
    </main>

  </body>
</html>
