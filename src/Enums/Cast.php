<?php

namespace DevAjMeireles\PagHiper\Enums;

use DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

enum Cast: string
{
    case Array              = 'array';
    case Json               = 'json';
    case Collect            = 'collect';
    case Collection         = 'collection';
    case Response           = 'response';
    case BilletNotification = 'billet';
    case PixNotification    = 'pix';

    /** @throws UnsupportedCastTypeExcetion */
    public function response(Response $response, string $index): Response|Collection|array|string
    {
        return (match ($this) {
            self::Response => fn () => $response,
            self::Json     => fn () => $this->toJson($response, $index),
            self::Array    => fn () => $this->toArray($response, $index),
            self::Collection, self::Collect => fn () => $this->toCollect($response, $index),
            default => throw new UnsupportedCastTypeExcetion($this->name),
        })();
    }

    public function toArray(Response $response, string $index): array
    {
        return $response->json($index);
    }

    public function toJson(Response $response, string $index): string
    {
        return collect($this->toArray($response, $index))->toJson();
    }

    public function toCollect(Response $response, string $index): Collection
    {
        return collect($this->toArray($response, $index));
    }
}
