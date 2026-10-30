<?php

class Encuesta
{
    private $id;
    private $titulo;
    private $descripcion;
    private $preguntas = [];

    public function __construct($id, $titulo, $descripcion)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function agregarPregunta($pregunta)
    {
        $this->preguntas[] = $pregunta;
    }

    public function getPreguntas()
    {
        return $this->preguntas;
    }
}
