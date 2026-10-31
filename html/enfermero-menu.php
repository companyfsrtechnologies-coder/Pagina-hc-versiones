<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'enfermero') {
    header("Location: prohibido.php");
    exit;
}
require_once('conect-bd-xampp.php');
$sqldocs = "SELECT * FROM documento";;
$resultadoDocs = $conn->execute_query($sqldocs);
$resultadoPaginas = null;
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menú enfermero</title>
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
    <div class="universal-container-men-enf">
      <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"> Menú de enfermería </a>
          <a href="../index.php" class="btn btn-primary btn-help">Volver al inicio</a>
        </div>
      </nav>
      <header class="decorado-men-admin"></header>
      <div class="alta-baja-mod-docs">
        <section class="altas-baja-mod-encuestas container py-4">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div
        class="card-header bg-white border-bottom d-flex flex-column flex-md-row
              align-items-md-center justify-content-between gap-3 p-4"
     >
      <div>
        <h2 class="h4 mb-1 text-dark">Documentos registrados</h2>
        <p class="text-secondary mb-0">
          Gestiona los documentos del sistema.
        </p>
      </div>
      <a
        href="enfermero-edit-text.php"
        class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2"
      >
        Nuevo documento
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th scope="col" class="ps-4">Título</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Categoría</th>
            <th scope="col">Fecha de actualización</th>
            <th scope="col" class="text-center pe-4">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($resultadoDocs->num_rows > 0): ?>
            <?php foreach ($resultadoDocs as $fila): ?>
              <?php
              $idPagina = 0;

              ?>
              <tr>
                <td class="ps-4 fw-semibold text-dark" style="display: none;">
                  <?php echo htmlspecialchars($fila['id_docuemnto']); ?>
                </td>

                <td class="ps-4 fw-semibold text-dark">
                  <?php echo htmlspecialchars($fila['titulo']); ?>
                </td>

                <td>
                  <span class="badge text-bg-primary rounded-pill px-3 py-2">
                    <?php echo htmlspecialchars($fila['especialidad']); ?>
                  </span>
                </td>

                <td>
                  <span class="badge text-bg-success rounded-pill px-3 py-2">
                    <?php echo htmlspecialchars($fila['categoria']); ?>
                  </span>
                </td>
                
                <td>
                  <span class="badge text-bg-info rounded-pill px-3 py-2">
                    <?php echo htmlspecialchars($fila['fecha_act']); ?>
                  </span>
                </td>
                <td class="text-center pe-4">
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="enfermero-edit-text.php?id=<?php echo (int)$fila['id_docuemnto']; ?>" class="btn btn-outline-primary">
                      <i class="fa-solid fa-edit"></i> <span>Editar</span>
                    </a>
                    
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      aria-label="Eliminar documento"
                      data-bs-toggle="modal"
                      data-bs-target="#confirmarEliminacionDocumento"
                      data-delete-url="borrar.php?id_docuemnto=<?php echo (int)$fila['id_docuemnto']; ?>"
                      data-document-title="<?php echo htmlspecialchars($fila['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
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
                <i class="fa-solid fa-file-circle-xmark fs-3 d-block mb-3"></i>
                No hay documentos registrados.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  </section>
      </div>
      </div>
    </div>
  <div class="modal fade" id="confirmarEliminacionDocumento" tabindex="-1" aria-labelledby="tituloConfirmacionEliminacion" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="tituloConfirmacionEliminacion">Eliminar documento</h2>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas eliminar <strong id="tituloDocumentoEliminar"></strong>? Esta acción no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="confirmarEliminarDocumento" class="btn btn-danger">Eliminar documento</a>
        </div>
      </div>
    </div>
  </div>
  <script>
    const confirmacionEliminacionDocumento = document.getElementById('confirmarEliminacionDocumento');

    confirmacionEliminacionDocumento.addEventListener('show.bs.modal', (event) => {
      const botonEliminar = event.relatedTarget;
      confirmacionEliminacionDocumento.querySelector('#tituloDocumentoEliminar').textContent =
        botonEliminar.getAttribute('data-document-title');
      confirmacionEliminacionDocumento.querySelector('#confirmarEliminarDocumento').href =
        botonEliminar.getAttribute('data-delete-url');
    });
  </script>
  </body>
</html>

