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

        $filasEncuestas = $this->conn->query("SELECT * FROM encuesta")->fetch_all(MYSQLI_ASSOC);

        foreach ($filasEncuestas as $filaEncuesta) {
            $encuesta = new Encuesta($filaEncuesta['id'], $filaEncuesta['titulo'], $filaEncuesta['descripcion']);

            $filasPreguntas = $this->conn->execute_query(
                "SELECT * FROM pregunta WHERE encuesta_id = ?",
                [$filaEncuesta['id']]
            )->fetch_all(MYSQLI_ASSOC);

            foreach ($filasPreguntas as $filaPregunta) {
                $respuesta = $this->crearRespuesta($filaPregunta['tipo']);
                $pregunta = new Pregunta($filaPregunta['id'], $filaPregunta['texto'], $respuesta);
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
        $resultado = $this->conexion->execute_query(
            "INSERT INTO encuesta (titulo, descripcion, fecha_creacion, id_admin) VALUES (?, ?, CURDATE(), ?)",
            [$encuesta->getTitulo(), $encuesta->getDescripcion(), $idAdmin]
        );

        if (!$resultado) {
            throw new Exception("No se pudo crear la encuesta: " . $this->conexion->error);
        }

        $encuestaId = $this->conexion->insert_id;

        foreach ($encuesta->getPreguntas() as $pregunta) {
            $this->conexion->execute_query(
                "INSERT INTO pregunta (encuesta_id, texto, tipo) VALUES (?, ?, ?)",
                [$encuestaId, $pregunta->getTexto(), $pregunta->getTipo()]
            );
        }
    }

    public function yaRespondio($encuesta, $email)
    {
        $resultado = $this->conexion->execute_query(
            "SELECT * FROM participacion WHERE encuesta_id = ? AND email = ?",
            [$encuesta->getId(), $email]
        );
        return $resultado->num_rows > 0;
    }

    public function guardarRespuestas($encuesta, $email)
    {
        $this->conexion->execute_query(
            "INSERT INTO participacion (encuesta_id, email) VALUES (?, ?)",
            [$encuesta->getId(), $email]
        );

        foreach ($encuesta->getPreguntas() as $pregunta) {
            $this->conexion->execute_query(
                "INSERT INTO respuesta (pregunta_id, contenido) VALUES (?, ?)",
                [$pregunta->getId(), $pregunta->getRespuesta()->getValor()]
            );
        }
    }

    // Devuelve un array con todos los valores respondidos para una pregunta
    public function obtenerValores($pregunta)
    {
        $filas = $this->conexion->execute_query(
            "SELECT contenido FROM respuesta WHERE pregunta_id = ?",
            [$pregunta->getId()]
        )->fetch_all(MYSQLI_ASSOC);

        $valores = [];
        foreach ($filas as $fila) {
            $valores[] = $fila['valor'];
        }
        return $valores;
    }
}
