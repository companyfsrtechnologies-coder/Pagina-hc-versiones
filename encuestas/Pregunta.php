<?php

class Pregunta
{
    private $titulo;
    private $texto;
    private $obligatoria;
    private $tipo;

    public function __construct(string $titulo, string $texto)
    {
        $this->titulo = $titulo;
        $this->texto = $texto;
    }
}
