<?php

// Clase padre. Cada tipo de respuesta (binaria, escala, texto)
// hereda de esta clase y sobrescribe sus métodos.
class Respuesta
{
    protected $valor = "";

    public function getTipo(): string
    {
        return "";
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }

    // Devuelve el HTML del campo para responder
    public function mostrarHtml($nombre): string
    {
        return "";
    }

    // Recibe todos los valores guardados y devuelve el resultado
    public function mostrarResultado($valores): string
    {
        return "";
    }
}
