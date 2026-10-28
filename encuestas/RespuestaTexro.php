<?php

require_once("Respuesta.php");

class RespuestaTexto extends Respuesta
{
    public function getTipo(): string
    {
        return "texto";
    }

    public function mostrarHtml($nombre): string
    {
        return "<textarea name='$nombre' required></textarea>";
    }

    public function mostrarResultado($valores): string
    {
        return implode("<br>", $valores);
    }
}
