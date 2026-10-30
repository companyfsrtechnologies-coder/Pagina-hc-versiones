<?php
require_once('conect-bd.php');

$rolesPermitidos = ['paciente', 'administrador', 'enfermero'];
$estadosPermitidos = ['Activo', 'No activo', 'Suspendido'];
$esEdicion = false;
$datosUsuario = null;
$error = null;
$idUsuario = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = filter_var($_POST['id_usuario'] ?? 0, FILTER_VALIDATE_INT) ?: 0;
} else {
    $idUsuario = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT) ?: 0;
}
$modoEdicion = $idUsuario > 0;

if ($idUsuario > 0) {
    $consultaUsuario = $conn->prepare(
        'SELECT id_usuario, nombre, estado, rol FROM usuario WHERE id_usuario = ?'
    );
    $consultaUsuario->bind_param('i', $idUsuario);
    $consultaUsuario->execute();
    $datosUsuario = $consultaUsuario->get_result()->fetch_assoc() ?: null;
    $esEdicion = $datosUsuario !== null;

    if (!$esEdicion) {
        $error = 'No se encontró el usuario que querés modificar.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($idUsuario === 0 || $esEdicion)) {
    $nombre = trim($_POST['nombre'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (
        $nombre === ''
        || !in_array($estado, $estadosPermitidos, true)
        || !in_array($rol, $rolesPermitidos, true)
        || (!$esEdicion && $contrasena === '')
    ) {
        $error = 'Completá los campos con valores válidos.';
    } else {
        if ($esEdicion) {
            if ($contrasena !== '') {
                $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
                $consulta = $conn->prepare(
                    'UPDATE usuario SET nombre = ?, estado = ?, contrasena = ?, rol = ? WHERE id_usuario = ?'
                );
                $consulta->bind_param('ssssi', $nombre, $estado, $contrasenaHash, $rol, $idUsuario);
            } else {
                $consulta = $conn->prepare(
                    'UPDATE usuario SET nombre = ?, estado = ?, rol = ? WHERE id_usuario = ?'
                );
                $consulta->bind_param('sssi', $nombre, $estado, $rol, $idUsuario);
            }
        } else {
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $consulta = $conn->prepare(
                'INSERT INTO usuario (nombre, estado, contrasena, rol) VALUES (?, ?, ?, ?)'
            );
            $consulta->bind_param('ssss', $nombre, $estado, $contrasenaHash, $rol);
        }

        $consulta->execute();
        header('Location: admin-menu.php');
        exit;
    }
}

$nombreFormulario = $_POST['nombre'] ?? ($datosUsuario['nombre'] ?? '');
$estadoFormulario = $_POST['estado'] ?? ($datosUsuario['estado'] ?? 'Activo');
$rolFormulario = $_POST['rol'] ?? ($datosUsuario['rol'] ?? 'paciente');
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $modoEdicion ? 'Editar usuario' : 'Crear usuario'; ?> | HCV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/admin-and-enfermeros-estilos.css" />
    <link rel="stylesheet" href="../css/botones-estilos.css" />
    <style>
      .pagina-usuario {
        min-height: 100vh;
        color: #1f2937;
      }
      .usuario-contenido {
        width: min(100% - 2rem, 760px);
        margin: 0 auto;
        padding: clamp(2rem, 6vw, 4rem) 0;
      }
      .usuario-tarjeta {
        border: 1px solid rgba(148, 163, 184, 0.28);
        border-radius: 1.25rem;
        background: #fff;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        overflow: hidden;
      }
      .usuario-encabezado {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: clamp(1.5rem, 4vw, 2.25rem);
        border-bottom: 1px solid rgba(148, 163, 184, 0.22);
        background: linear-gradient(135deg, #edf6ff 0%, #f7fbff 100%);
      }
      .usuario-icono {
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
      .usuario-formulario {
        display: grid;
        gap: 1.25rem;
        padding: clamp(1.5rem, 4vw, 2.25rem);
      }
      .usuario-formulario .form-label {
        color: #173d63;
        font-weight: 650;
      }
      .usuario-formulario .form-control,
      .usuario-formulario .form-select {
        min-height: 3rem;
        border-color: #cbd8e4;
        border-radius: 0.7rem;
      }
      .usuario-formulario .form-control:focus,
      .usuario-formulario .form-select:focus {
        border-color: #2a7acb;
        box-shadow: 0 0 0 0.2rem rgba(42, 122, 203, 0.15);
      }
      .usuario-acciones {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding-top: 0.5rem;
      }
      @media (max-width: 575.98px) {
        .usuario-acciones > * {
          width: 100%;
        }
      }
    </style>
  </head>
  <body class="pagina-usuario">
    <nav class="navbar navbar-dark bg-dark">
      <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand" href="admin-menu.php">Administración</a>
        <a href="admin-menu.php" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
          Volver al menú
        </a>
      </div>
    </nav>

    <main class="usuario-contenido">
      <section class="usuario-tarjeta" aria-labelledby="titulo-formulario">
        <header class="usuario-encabezado">
          <span class="usuario-icono" aria-hidden="true">
            <i class="fa-solid <?= $modoEdicion ? 'fa-user-pen' : 'fa-user-plus'; ?>"></i>
          </span>
          <div>
            <p class="text-uppercase small fw-bold text-primary mb-1">Gestión de usuarios</p>
            <h1 class="h3 mb-1 text-dark" id="titulo-formulario">
              <?= $modoEdicion ? 'Editar usuario' : 'Crear usuario'; ?>
            </h1>
            <p class="text-secondary mb-0">
              <?= $modoEdicion ? 'Actualizá los datos y permisos de esta cuenta.' : 'Completá los datos para registrar una cuenta nueva.'; ?>
            </p>
          </div>
        </header>

        <?php if ($error !== null): ?>
          <div class="alert alert-danger m-4 mb-0" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>

        <?php if ($idUsuario === 0 || $esEdicion): ?>
        <form method="POST" class="usuario-formulario">
          <?php if ($esEdicion): ?>
            <input type="hidden" name="id_usuario" value="<?= (int)$datosUsuario['id_usuario']; ?>">
          <?php endif; ?>

          <div>
            <label for="nombre" class="form-label">Nombre y apellido</label>
            <input
              type="text"
              class="form-control"
              id="nombre"
              name="nombre"
              value="<?= htmlspecialchars($nombreFormulario, ENT_QUOTES, 'UTF-8'); ?>"
              autocomplete="name"
              maxlength="255"
              required
            >
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="estado" class="form-label">Estado de la cuenta</label>
              <select class="form-select" id="estado" name="estado" required>
                <?php foreach ($estadosPermitidos as $estado): ?>
                  <option value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>" <?= $estadoFormulario === $estado ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="rol" class="form-label">Rol</label>
              <select class="form-select" id="rol" name="rol" required>
                <?php foreach ($rolesPermitidos as $rol): ?>
                  <option value="<?= htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?>" <?= $rolFormulario === $rol ? 'selected' : ''; ?>>
                    <?= htmlspecialchars(ucfirst($rol), ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div>
            <label for="contrasena" class="form-label">
              Contraseña<?= $esEdicion ? ' nueva' : ''; ?>
            </label>
            <input
              type="password"
              class="form-control"
              id="contrasena"
              name="contrasena"
              autocomplete="new-password"
              <?= $esEdicion ? '' : 'required'; ?>
            >
            <div class="form-text">
              <?= $esEdicion ? 'Dejá este campo vacío para conservar la contraseña actual.' : 'Elegí una contraseña para la cuenta.'; ?>
            </div>
          </div>

          <div class="usuario-acciones">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk me-1" aria-hidden="true"></i>
              <?= $esEdicion ? 'Guardar cambios' : 'Crear usuario'; ?>
            </button>
            <a href="admin-menu.php" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </form>
        <?php endif; ?>
      </section>
    </main>
  </body>
</html>
