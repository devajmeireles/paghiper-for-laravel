<?php

namespace DevAjMeireles\PagHiper\Core\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

trait InteractWithCasts
{
    protected Response $response;

    protected function cast(): Response|Collection|array
    {
        return (match ($this->cast) {
            'response' => fn () => $this->toResponse(),
            'json'     => fn () => $this->toArray(),
            'collect'  => fn () => $this->toCollect(),
        })();
    }

    public function toResponse(): Response
    {
        return $this->response;
    }

    public function toArray(): array
    {
        return $this->response->json('create_request');
    }

    public function toCollect(): Collection
    {
        return collect($this->toArray());
    }
}
