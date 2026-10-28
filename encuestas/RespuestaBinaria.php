<?php
<<<<<<< HEAD
=======

>>>>>>> 237a3831ac6384806821db232ba47c3f3503fc89
require_once("Respuesta.php");

class RespuestaBinaria extends Respuesta
{
    public function getTipo(): string
    {
        return "binaria";
    }

    public function mostrarHtml($nombre): string
    {
        return "<label><input type='radio' name='$nombre' value='si' required> Sí</label>
                <label><input type='radio' name='$nombre' value='no'> No</label>";
    }

    public function mostrarResultado($valores): string
    {
        $si = 0;
        $no = 0;
        foreach ($valores as $valor) {
            if ($valor == "si") {
                $si++;
            } else {
                $no++;
            }
        }
        return "Sí: $si - No: $no";
    }
}
