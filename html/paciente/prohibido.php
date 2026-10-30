<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso prohibido</title>
    <link rel="stylesheet" href="../css/paciente-estilos.css" />
    <style>
        body {
            display: grid;
            place-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(180deg, #edf6ff 0%, #eaf3ff 100%);
            font-family: "Segoe UI", sans-serif;
        }
        .prohibido-card {
            width: min(520px, 90%);
            padding: 2.25rem 2rem;
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(148, 163, 184, 0.28);
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            text-align: center;
        }
        .prohibido-card h1 {
            margin: 0 0 0.75rem;
            color: #173d63;
            font-size: clamp(2rem, 5vw, 2.7rem);
        }
        .prohibido-card p {
            margin: 0;
            color: #5d7288;
            line-height: 1.7;
        }
    </style>
</head>
<body>
    <div class="prohibido-card">
        <h1>Acceso prohibido</h1>
        <p>No tienes permisos para ingresar a esta sección del sistema.</p>
    </div>
</body>
</html>