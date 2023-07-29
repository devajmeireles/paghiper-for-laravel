<?php

namespace DevAjMeireles\PagHiper\Core\Enums;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

enum Cast: string
{
    case Array      = 'array';
    case Json       = 'json';
    case Collect    = 'collect';
    case Collection = 'collection';
    case Response   = 'response';
    case Dto        = 'dto';

    /** @throws Exception */
    public function response(Response $response, string $index): Response|Collection|array
    {
        /** @phpstan-ignore-next-line */
        return (match ($this) {
            self::Response => fn () => $response,
            self::Array, self::Json => fn () => $this->toArray($response, $index),
            self::Collection, self::Collect => fn () => $this->toCollect($response, $index),
            default => throw new Exception("Unsupported cast {$this->value}"),
        })();
    }

    public function toArray(Response $response, string $index): array
    {
        return $response->json($index);
    }

    public function toCollect(Response $response, string $index): Collection
    {
        return collect($this->toArray($response, $index));
    }
}
