<?php

class Pregunta
{
    private $id;
    private $texto;
    private $respuesta;

    public function __construct($id, $texto, $respuesta)
    {
        $this->id = $id;
        $this->texto = $texto;
        $this->respuesta = $respuesta;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTexto()
    {
        return $this->texto;
    }

    public function getRespuesta()
    {
        return $this->respuesta;
    }

    // El tipo de la pregunta lo sabe su respuesta
    public function getTipo()
    {
        return $this->respuesta->getTipo();
    }
}
