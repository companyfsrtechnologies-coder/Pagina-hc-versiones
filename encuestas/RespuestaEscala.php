<?php

require_once("Respuesta.php");

<<<<<<< HEAD
class RespuestaEscala extends Respuesta
{
    public function getTipo(): string
    {
        return "escala";
=======
class RespuestaTexto extends Respuesta
{
    public function getTipo(): string
    {
        return "texto";
>>>>>>> 237a3831ac6384806821db232ba47c3f3503fc89
    }

    public function mostrarHtml($nombre): string
    {
<<<<<<< HEAD
        return "<input type='number' name='$nombre' min='1' max='10' required>";
=======
        return "<textarea name='$nombre' required></textarea>";
>>>>>>> 237a3831ac6384806821db232ba47c3f3503fc89
    }

    public function mostrarResultado($valores): string
    {
<<<<<<< HEAD
        if (sizeof($valores) == 0) {
            return "Sin respuestas";
        }

        $acumulado = 0;
        foreach ($valores as $valor) {
            $acumulado += $valor;
        }

        return "Promedio: " . round($acumulado / sizeof($valores), 1);
=======
        return implode("<br>", $valores);
>>>>>>> 237a3831ac6384806821db232ba47c3f3503fc89
    }
}
