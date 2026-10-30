<?php

require_once("Encuesta.php");
require_once("Pregunta.php");
require_once("RespuestaBinaria.php");
require_once("RespuestaEscala.php");
require_once("RespuestaTexto.php");

// Tiene la lista de encuestas y es el único que habla con la base de datos
class SistemaEncuestas
{
    private $conexion;
    private $encuestas = [];

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Según el tipo guardado en la base, crea la subclase que corresponde
    public function crearRespuesta($tipo)
    {
        switch ($tipo) {
            case "binaria":
                return new RespuestaBinaria();
            case "escala":
                return new RespuestaEscala();
            case "texto":
                return new RespuestaTexto();
        }
    }

    // Lee la base de datos y arma los objetos
    public function cargarEncuestas()
    {
        $this->encuestas = [];

        $filasEncuestas = $this->conexion->query(
            "SELECT id_encuesta, titulo, descripcion
             FROM encuesta
             WHERE estado = 'Publicada'
             ORDER BY fecha_public DESC"
        )->fetch_all(MYSQLI_ASSOC);

        foreach ($filasEncuestas as $filaEncuesta) {
            $encuesta = new Encuesta(
                $filaEncuesta['id_encuesta'],
                $filaEncuesta['titulo'],
                $filaEncuesta['descripcion']
            );

            $filasPreguntas = $this->conexion->execute_query(
                "SELECT id_pregunta, texto, tipo
                 FROM pregunta
                 WHERE id_encuesta = ?",
                [$filaEncuesta['id_encuesta']]
            )->fetch_all(MYSQLI_ASSOC);

            foreach ($filasPreguntas as $filaPregunta) {
                $respuesta = $this->crearRespuesta($filaPregunta['tipo']);
                $pregunta = new Pregunta(
                    $filaPregunta['id_pregunta'],
                    $filaPregunta['texto'],
                    $respuesta
                );
                $encuesta->agregarPregunta($pregunta);
            }

            $this->encuestas[] = $encuesta;
        }
    }

    public function getEncuestas()
    {
        return $this->encuestas;
    }

    public function getEncuesta($id)
    {
        foreach ($this->encuestas as $encuesta) {
            if ($encuesta->getId() == $id) {
                return $encuesta;
            }
        }
        return null;
    }

    public function guardarEncuesta($encuesta, $idAdmin)
    {
        if ($encuesta->getPreguntas() === []) {
            throw new RuntimeException('La encuesta debe tener al menos una pregunta.');
        }

        $this->conexion->begin_transaction();

        try {
            $resultadoEncuesta = $this->conexion->execute_query(
                "INSERT INTO encuesta
                 (titulo, descripcion, fecha_public, id_admin, estado)
                 VALUES (?, ?, CURRENT_DATE, ?, 'Publicada')",
                [$encuesta->getTitulo(), $encuesta->getDescripcion(), $idAdmin]
            );

            if ($resultadoEncuesta === false) {
                throw new RuntimeException($this->conexion->error);
            }

            $encuestaId = $this->conexion->insert_id;

            foreach ($encuesta->getPreguntas() as $pregunta) {
                $tipo = $pregunta->getTipo();
                if (!in_array($tipo, ['binaria', 'escala', 'texto'], true)) {
                    throw new RuntimeException('Tipo de pregunta inválido.');
                }

                $resultadoPregunta = $this->conexion->execute_query(
                    "INSERT INTO pregunta (id_encuesta, texto, tipo) VALUES (?, ?, ?)",
                    [$encuestaId, $pregunta->getTexto(), $tipo]
                );

                if ($resultadoPregunta === false) {
                    throw new RuntimeException($this->conexion->error);
                }
            }

            $this->conexion->commit();
        } catch (Throwable $error) {
            $this->conexion->rollback();
            throw new RuntimeException(
                'No se pudo guardar la encuesta: ' . $error->getMessage(),
                0,
                $error
            );
        }
    }

    public function yaRespondio($encuesta, $idPaciente)
    {
        $resultado = $this->conexion->execute_query(
            "SELECT 1 FROM partisipacion
             WHERE id_encuesta = ? AND id_paciente = ?",
            [$encuesta->getId(), $idPaciente]
        );
        return $resultado->num_rows > 0;
    }

    public function guardarRespuestas($encuesta, $idPaciente)
    {
        $this->conexion->execute_query(
            "INSERT INTO partisipacion (id_paciente, id_encuesta) VALUES (?, ?)",
            [$idPaciente, $encuesta->getId()]
        );

        foreach ($encuesta->getPreguntas() as $pregunta) {
            $this->conexion->execute_query(
                "INSERT INTO respuesta (id_pregunta, valor) VALUES (?, ?)",
                [$pregunta->getId(), $pregunta->getRespuesta()->getValor()]
            );
        }
    }

    // Devuelve un array con todos los valores respondidos para una pregunta
    public function obtenerValores($pregunta)
    {
        $filas = $this->conexion->execute_query(
            "SELECT valor FROM respuesta WHERE id_pregunta = ?",
            [$pregunta->getId()]
        )->fetch_all(MYSQLI_ASSOC);

        $valores = [];
        foreach ($filas as $fila) {
            $valores[] = $fila['valor'];
        }
        return $valores;
    }
}
