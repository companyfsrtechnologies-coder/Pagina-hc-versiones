<?php

class Encuesta
{
    private $titulo;
    private $descripcion;
    private $estado;
    private $categoria;
    private $listaPreguntas = array();

    public function __construct(string $titulo, string $descripcion, string $categoria)
    {
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->categoria = $categoria;
        $this->estado = "borrador";
    }

    public function crearPregunta()
    {
        $pregunta = new Pregunta("titulo pregunta", "texto pregunnta");
        array_push($this->listaPreguntas, $pregunta);
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
        array_push($this->listaPreguntas, $pregunta);
    }

    public function getListaPreguntas()
    {
        return $this->listaPreguntas;
    }
}
