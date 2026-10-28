<?php

class Pregunta
{
    private $id;
    private $titulo;
    private $texto;
    private $respuesta;
    private $obligatoria;

    public function __construct($id = null, $texto = "", $respuesta = null)
    {
        $this->id = $id;
        $this->titulo = $texto;
        $this->texto = $texto;
        $this->respuesta = $respuesta;
        $this->obligatoria = true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTexto()
    {
        return $this->texto;
    }

    public function getTipo()
    {
        return $this->respuesta ? $this->respuesta->getTipo() : "texto";
    }

    public function getRespuesta()
    {
        return $this->respuesta;
    }
}
