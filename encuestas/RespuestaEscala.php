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
        return "<label class='escala-opcion'><input type='radio' name='$nombre' value='1' required> 1 - Mal</label>
                <label class='escala-opcion'><input type='radio' name='$nombre' value='2'> 2</label>
                <label class='escala-opcion'><input type='radio' name='$nombre' value='3'> 3</label>
                <label class='escala-opcion'><input type='radio' name='$nombre' value='4'> 4</label>
                <label class='escala-opcion'><input type='radio' name='$nombre' value='5'> 5 - Excelente</label>";
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
