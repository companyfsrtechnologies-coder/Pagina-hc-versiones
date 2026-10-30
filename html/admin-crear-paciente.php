<?php

require_once('conect-bd-xampp.php');

$error = null;
$cedulaFormulario = trim($_POST['cedula'] ?? '');
$fechaFormulario = $_POST['fecha_ingeso'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = filter_var($cedulaFormulario, FILTER_VALIDATE_INT);
    $fechaIngreso = $fechaFormulario;

    $fechaValida = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
    if (
        $cedula === false
        || $cedula <= 0
        || !$fechaValida
        || $fechaValida->format('Y-m-d') !== $fechaIngreso
    ) {
        $error = 'Ingresá una cédula y una fecha de ingreso válidas.';
    } else {
        $verificar = $conn->prepare('SELECT id FROM paciente WHERE cedula = ? LIMIT 1');
        $verificar->bind_param('i', $cedula);
        $verificar->execute();

        if ($verificar->get_result()->num_rows > 0) {
            $error = 'Ya existe un paciente registrado con esa cédula.';
        } else {
            $conn->begin_transaction();
            $crearUsuario = $conn->prepare('INSERT INTO usuario () VALUES ()');
            $crearPaciente = $conn->prepare(
                'INSERT INTO paciente (id, cedula, fecha_ingeso) VALUES (?, ?, ?)'
            );

            if ($crearUsuario === false || $crearPaciente === false || !$crearUsuario->execute()) {
                $conn->rollback();
                $error = 'No se pudo crear el usuario del paciente.';
            } else {
                $idUsuario = $conn->insert_id;
                $crearPaciente->bind_param('iis', $idUsuario, $cedula, $fechaIngreso);

                if (!$crearPaciente->execute()) {
                    $conn->rollback();
                    $error = 'No se pudo guardar el paciente.';
                } else {
                    $conn->commit();
                    header('Location: admin-menu.php');
                    exit;
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear paciente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin-and-enfermeros-estilos.css">
    <link rel="stylesheet" href="../css/botones-estilos.css">
    <style>
      .paciente-contenido {
        width: min(100% - 2rem, 760px);
        margin: 0 auto;
        padding: clamp(2rem, 6vw, 4rem) 0;
      }

      .paciente-tarjeta {
        overflow: hidden;
      }

      .paciente-encabezado {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: clamp(1.5rem, 4vw, 2.25rem);
        background: linear-gradient(135deg, #edf6ff 0%, #f7fbff 100%);
        border-bottom: 1px solid var(--site-border);
      }

      .paciente-icono {
        display: grid;
        width: 3.25rem;
        height: 3.25rem;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 1rem;
        color: #fff;
        background: linear-gradient(135deg, #2a7acb, #1d5fa5);
        font-size: 1.35rem;
      }

      .paciente-formulario {
        display: grid;
        gap: 1.25rem;
        padding: clamp(1.5rem, 4vw, 2.25rem);
      }

      .paciente-formulario .form-label {
        color: var(--site-primary);
        font-weight: 650;
      }

      .paciente-formulario .form-control {
        min-height: 3rem;
        border-color: #cbd8e4;
        border-radius: 0.7rem;
      }

      .paciente-formulario .form-control:focus {
        border-color: var(--site-accent);
        box-shadow: 0 0 0 0.2rem rgba(42, 122, 203, 0.15);
      }

      .paciente-acciones {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding-top: 0.5rem;
      }

      @media (max-width: 575.98px) {
        .paciente-acciones > * {
          width: 100%;
        }
      }
    </style>
  </head>
  <body class="pagina-usuario">
    <nav class="navbar navbar-dark bg-dark">
      <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand" href="admin-menu.php">Administración</a>
        <a href="admin-menu.php" class="btn btn-outline-light btn-sm">Volver al menú</a>
      </div>
    </nav>

    <main class="paciente-contenido">
      <section class="card paciente-tarjeta" aria-labelledby="titulo-formulario">
        <header class="paciente-encabezado">
          <span class="paciente-icono" aria-hidden="true">
            <i class="fa-solid fa-hospital-user"></i>
          </span>
          <div>
            <p class="text-uppercase small fw-bold text-primary mb-1">Gestión de pacientes</p>
            <h1 class="h3 mb-1 text-dark" id="titulo-formulario">Crear paciente</h1>
            <p class="text-secondary mb-0">Registrá los datos necesarios para el acceso del paciente.</p>
          </div>
        </header>

        <?php if ($error !== null): ?>
          <div class="alert alert-danger m-4 mb-0" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="paciente-formulario">
          <div>
            <label for="cedula" class="form-label">Cédula</label>
            <input
              type="number"
              class="form-control"
              id="cedula"
              name="cedula"
              min="1"
              value="<?= htmlspecialchars($cedulaFormulario, ENT_QUOTES, 'UTF-8'); ?>"
              required
            >
          </div>

          <div>
            <label for="fecha_ingeso" class="form-label">Fecha de ingreso</label>
            <input
              type="date"
              class="form-control"
              id="fecha_ingeso"
              name="fecha_ingeso"
              value="<?= htmlspecialchars($fechaFormulario, ENT_QUOTES, 'UTF-8'); ?>"
              required
            >
          </div>

          <div class="paciente-acciones">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-user-plus me-1" aria-hidden="true"></i>
              Crear paciente
            </button>
            <a href="admin-menu.php" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </form>
      </section>
    </main>
  </body>
</html>

