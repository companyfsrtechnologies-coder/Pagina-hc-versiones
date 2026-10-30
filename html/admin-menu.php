<?php
session_start();



require_once('conect-bd-xampp.php');
$sql = "SELECT id AS id_usuario, nombre, rol, 'Activo' AS estado FROM funcionafio";
$resultadoUsuarios = $conn->execute_query($sql);
$sqlPacientes = "SELECT id AS id_usuario, cedula, fecha_ingeso FROM paciente ORDER BY cedula ASC";
$resultadoPacientes = $conn->execute_query($sqlPacientes);
$sqlEncuestas = "SELECT id_encuesta, titulo, descripcion, fecha_public AS fecha_creacion FROM encuesta";

$resultado = $conn->execute_query($sqlEncuestas);

$datosEncuestas = $resultado->fetch_all(MYSQLI_ASSOC);



?>
<!doctype html>
 <html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Menú de administración</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css"
      integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="../css/admin-and-enfermeros-estilos.css" />
    <link rel="stylesheet" href="../css/botones-estilos.css" />

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
  </head>

  <body>
    <div class="universal-container-men-admin">
      <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"> Menú de administración </a>
          <a href="../index.php" class="btn btn-primary btn-help">Volver al inicio</a>
        </div>
      </nav>
      <header class="decorado-men-admin"></header>
      
     <section class="altas-baja-mod-encuestas container py-4">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div
        class="card-header bg-white border-bottom d-flex flex-column flex-md-row
              align-items-md-center justify-content-between gap-3 p-4"
     >
      <div>
        <h2 class="h4 mb-1 text-dark">Encuestas registradas</h2>
        <p class="text-secondary mb-0">
          Gestiona las encuestas del sistema.
        </p>
      </div>

      <a
        href="admin-crear-encuesta.php"
        class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2"
      >
        Nueva encuesta
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th scope="col" class="ps-4">Título</th>
            <th scope="col">Descripción</th>
            <th scope="col">Fecha de creación</th>
            <th scope="col" class="text-center pe-4">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($datosEncuestas): ?>
            <?php foreach ($datosEncuestas as $fila): ?>
              <tr>
                <td class="ps-4 fw-semibold text-dark">
                  <?php echo htmlspecialchars($fila['titulo']); ?>
                </td>

                <td>
                  <span class="badge text-bg-primary rounded-pill px-3 py-2">
                    <?php echo htmlspecialchars($fila['descripcion']); ?>
                  </span>
                </td>

                <td>
                  <span class="badge text-bg-success rounded-pill px-3 py-2">
                    <?php
                      $fechaCreacion = $fila['fecha_creacion'] ?? '';
                      echo htmlspecialchars(
                        $fechaCreacion === '' || substr($fechaCreacion, 0, 10) === '0000-00-00'
                          ? 'Sin fecha registrada'
                          : $fechaCreacion
                      );
                    ?>
                  </span>
                </td>

                <td class="text-center pe-4">
                  <div class="btn-group btn-group-sm" role="group">
                    <a
                      href="admin-crear-encuesta.php?id=<?php echo $fila['id_encuesta']; ?>"
                      class="btn btn-outline-primary"
                      aria-label="Editar encuesta"
                    >
                      <i class="fa-solid fa-pen-to-square"></i>
                      <span class="d-none d-md-inline ms-1">Editar</span>
                    </a>

                    <a
                      href="../encuestas/resultados.php?id=<?php echo (int)$fila['id_encuesta']; ?>"
                      class="btn btn-outline-secondary"
                      aria-label="Ver resultados de la encuesta"
                    >
                      <i class="fa-solid fa-chart-column"></i>
                      <span class="d-none d-md-inline ms-1">Resultados</span>
                    </a>

                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      aria-label="Eliminar encuesta"
                      data-bs-toggle="modal"
                      data-bs-target="#confirmarEliminacionEncuesta"
                      data-delete-url="eliminar-encuesta.php?id=<?= (int)$fila['id_encuesta']; ?>"
                      data-survey-title="<?= htmlspecialchars($fila['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
                    >
                      <i class="fa-solid fa-trash"></i>
                      <span class="d-none d-md-inline ms-1">Eliminar</span>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center py-5 text-secondary">
                <i class="fa-solid fa-users-slash fs-3 d-block mb-3"></i>
                No hay usuarios registrados.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  </section>
  <div class="modal fade" id="confirmarEliminacionEncuesta" tabindex="-1" aria-labelledby="tituloConfirmacionEliminacionEncuesta" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="tituloConfirmacionEliminacionEncuesta">Eliminar encuesta</h2>

        </div>
        <div class="modal-body">
          ¿Seguro que deseas eliminar la encuesta <strong id="tituloEncuestaEliminar"></strong>? Esta acción no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="confirmarEliminarEncuesta" class="btn btn-danger">Eliminar encuesta</a>
        </div>
      </div>
    </div>
  </div>
  <section class="altas-baja-mod-users container py-4">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div
        class="card-header bg-white border-bottom d-flex flex-column flex-md-row
              align-items-md-center justify-content-between gap-3 p-4"
     >
      <div>
        <h2 class="h4 mb-1 text-dark">Enfermeros y administradores</h2>
        <p class="text-secondary mb-0">
          Gestioná las cuentas y permisos del personal.
        </p>
      </div>

      <a
        href="admin-crearEditar-usuario.php"
        class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2"
      >
        <i class="fa-solid fa-user-plus"></i>
        Nuevo enfermero o administrador
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th scope="col" class="ps-4">Nombre</th>
            <th scope="col">Rol</th>
            <th scope="col">Estado</th>
            <th scope="col" class="text-center pe-4">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($resultadoUsuarios->num_rows> 0): ?>
            <?php foreach ($resultadoUsuarios as $fila): ?>
              <tr>
                <td class="ps-4 fw-semibold text-dark">
                  <?php echo htmlspecialchars($fila['nombre']); ?>
                </td>

                <td>
                  <span class="badge text-bg-primary rounded-pill px-3 py-2">
                    <?php echo htmlspecialchars($fila['rol']); ?>
                  </span>
                </td>
                <td>
                  <span class="badge text-bg-success rounded-pill px-3 py-2">
                    <?php echo htmlspecialchars($fila['estado']); ?>
                  </span>
                </td>
                
                <td class="text-center pe-4">
                  <div class="btn-group btn-group-sm" role="group">
                    <a
                      href="admin-crearEditar-usuario.php?id=<?= urlencode($fila['id_usuario']); ?>"
                      class="btn btn-outline-primary"
                      aria-label="Editar usuario"
                    >
                      <i class="fa-solid fa-pen-to-square"></i>
                      <span class="d-none d-md-inline ms-1">Editar</span>
                    </a>

                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      aria-label="Eliminar usuario"
                      data-bs-toggle="modal"
                      data-bs-target="#confirmarEliminacionUsuario"
                      data-delete-url="borrar.php?accion=eliminar_usuario&id=<?= (int)$fila['id_usuario']; ?>"
                      data-user-name="<?= htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                    >
                      <i class="fa-solid fa-trash"></i>
                      <span class="d-none d-md-inline ms-1">Eliminar</span>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center py-5 text-secondary">
                <i class="fa-solid fa-users-slash fs-3 d-block mb-3"></i>
                No hay usuarios registrados.
              </td>
            </tr>
          <?php endif; ?>
          
        </tbody>
      </table>
    </div>
  </div>
  </section>

  <section class="altas-baja-mod-users container py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-header bg-white border-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-4">
        <div>
          <h2 class="h4 mb-1 text-dark">Pacientes registrados</h2>
          <p class="text-secondary mb-0">Gestioná las cuentas de los pacientes.</p>
        </div>
        <a href="admin-crear-paciente.php" class="btn btn-success">
          <i class="fa-solid fa-hospital-user"></i>
          Nuevo paciente
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark">
            <tr>
              <th class="ps-4">Cédula</th>
              <th>Fecha de ingreso</th>
              <th class="text-center pe-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($resultadoPacientes->num_rows > 0): ?>
              <?php foreach ($resultadoPacientes as $paciente): ?>
                <tr>
                  <td class="ps-4 fw-semibold"><?= htmlspecialchars($paciente['cedula'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($paciente['fecha_ingeso'] ?? ''); ?></td>
                  <td class="text-center pe-4">
                    <a
                      href="borrar.php?accion=eliminar_paciente&id=<?= (int)$paciente['id_usuario']; ?>"
                      class="btn btn-outline-danger btn-sm"
                      onclick="return confirm('¿Seguro que deseas eliminar este paciente?');"
                    >
                      <i class="fa-solid fa-trash"></i>
                      Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-center py-5 text-secondary">No hay pacientes registrados.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
  <div class="modal fade" id="confirmarEliminacionUsuario" tabindex="-1" aria-labelledby="tituloConfirmacionEliminacionUsuario" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="tituloConfirmacionEliminacionUsuario">Eliminar usuario</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas eliminar al usuario <strong id="nombreUsuarioEliminar"></strong>? Esta acción no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="confirmarEliminarUsuario" class="btn btn-danger">Eliminar usuario</a>
        </div>
      </div>
    </div>
  </div>
  <script>
    const confirmacionEliminacionEncuesta = document.getElementById('confirmarEliminacionEncuesta');

    confirmacionEliminacionEncuesta.addEventListener('show.bs.modal', (event) => {
      const botonEliminar = event.relatedTarget;
      confirmacionEliminacionEncuesta.querySelector('#tituloEncuestaEliminar').textContent =
        botonEliminar.getAttribute('data-survey-title');
      confirmacionEliminacionEncuesta.querySelector('#confirmarEliminarEncuesta').href =
        botonEliminar.getAttribute('data-delete-url');
    });

    const confirmacionEliminacionUsuario = document.getElementById('confirmarEliminacionUsuario');

    confirmacionEliminacionUsuario.addEventListener('show.bs.modal', (event) => {
      const botonEliminar = event.relatedTarget;
      confirmacionEliminacionUsuario.querySelector('#nombreUsuarioEliminar').textContent =
        botonEliminar.getAttribute('data-user-name');
      confirmacionEliminacionUsuario.querySelector('#confirmarEliminarUsuario').href =
        botonEliminar.getAttribute('data-delete-url');
    });
  </script>
    </div>
  </body>
</html>
