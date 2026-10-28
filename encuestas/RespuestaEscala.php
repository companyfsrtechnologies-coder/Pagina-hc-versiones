<?php

require_once("Respuesta.php");

class RespuestaEscala extends Respuesta
{
    public function getTipo(): string
    {
        return "escala";
    }

    public function mostrarHtml($nombre): string
    {
        return "<input type='number' name='$nombre' min='1' max='10' required>";
    }

    public function mostrarResultado($valores): string
    {
        if (sizeof($valores) == 0) {
            return "Sin respuestas";
        }

        $acumulado = 0;
        foreach ($valores as $valor) {
            $acumulado += $valor;
        }

        return "Promedio: " . round($acumulado / sizeof($valores), 1);
    }
}
