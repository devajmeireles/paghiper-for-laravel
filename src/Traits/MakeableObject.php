<?php

namespace DevAjMeireles\PagHiper\Traits;

trait MakeableObject
{
    public static function make(...$parameters): self
    {
        if (count($parameters) === 1 && is_array($parameters[0])) {
            $parameters = $parameters[0];
        }

        return new self(...$parameters);
    }

    public function set(string $property, mixed $value): self
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }

        return $this;
    }
}
