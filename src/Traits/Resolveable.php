<?php

namespace DevAjMeireles\PagHiper\Traits;

trait Resolveable
{
    public function __construct(
        private readonly mixed $callback = null
    ) {
    }

    public function resolve(): mixed
    {
        if ($this->callback === null) {
            return null;
        }

        return call_user_func($this->callback);
    }
}
