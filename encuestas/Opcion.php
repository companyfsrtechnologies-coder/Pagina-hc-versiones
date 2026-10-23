<?php

class Opcion
{
    private $texto;
    private $valor;
    private $seleccionada;

    public function __construct(string $texto, string $valor)
    {
        $this->texto = $texto;
        $this->valor = $valor;
        $this->seleccionada = false;
    }

    public function seleccionar()
    {
        $this->seleccionada = true;
    }
}
