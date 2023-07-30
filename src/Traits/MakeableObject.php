<?php

namespace DevAjMeireles\PagHiper\Traits;

trait MakeableObject
{
    public static function make(...$parameters): self
    {
        if (count($parameters) === 1 && is_array($parameters[0])) {
            $parameters = $parameters[0];
        }

        return new static(...$parameters);
    }
}
