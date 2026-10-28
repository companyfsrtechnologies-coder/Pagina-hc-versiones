<?php

class Encuesta
{
    private $id;
    private $titulo;
    private $descripcion;
    private $estado;
    private $categoria;
    private $listaPreguntas = array();

    public function __construct($id = null, $titulo = "", $descripcion = "", $categoria = "general")
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->categoria = $categoria;
        $this->estado = "borrador";
    }

    public function crearPregunta()
    {
        $pregunta = new Pregunta(null, "Título de la pregunta", new RespuestaTexto());
        $this->listaPreguntas[] = $pregunta;
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

    public function getTituloYDescripcion()
    {
        return $this->titulo . " Desc: " . $this->descripcion . " Cat: " . $this->categoria;
    }

    public function setTitulo(string $nuevoTitulo)
    {
        $this->titulo = $nuevoTitulo;
    }

    public function agregarPregunta(Pregunta $pregunta)
    {
        $this->listaPreguntas[] = $pregunta;
    }

    public function getListaPreguntas()
    {
        return $this->listaPreguntas;
    }

    public function getPreguntas()
    {
        return $this->listaPreguntas;
    }
}
