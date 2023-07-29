<?php

namespace DevAjMeireles\PagHiper\Core\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

trait InteractWithCasts
{
    protected Response $response;

    protected function cast(string $index): Response|Collection|array
    {
        /** @phpstan-ignore-next-line */
        return (match ($this->cast) {
            'response' => fn () => $this->toResponse(),
            'json', 'array' => fn () => $this->toArray($index),
            'collect', 'collection' => fn () => $this->toCollect($index),
        })();
    }

    public function toResponse(): Response
    {
        return $this->response;
    }

    public function toArray(string $index): array
    {
        return $this->response->json($index);
    }

    public function toCollect(string $index): Collection
    {
        return collect($this->toArray($index));
    }
}
