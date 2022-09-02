<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidacionAfip implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // primero, que tenga 13 caracteres
        if (strlen($value) != 13) {
            return false;
        }

        $rv = false;
        $resultado = 0;
        $cuit_nro = str_replace("-", "", $value);

        $codes = "6789456789";
        $cuit_long = intVal($cuit_nro);
        $verificador = intVal($cuit_nro[strlen($cuit_nro) - 1]);

        $x = 0;

        while ($x < 10) {
            $digitoValidador = intVal(substr($codes, $x, 1));
            $digito = intVal(substr($cuit_nro, $x, 1));
            $digitoValidacion = $digitoValidador * $digito;
            $resultado += $digitoValidacion;
            $x++;
        }
        $resultado = intVal($resultado) % 11;
        $rv = $resultado == $verificador;
        return $rv;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute no es un CUIT/CUIL válido';
    }
}
