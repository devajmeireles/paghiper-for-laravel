<?php

namespace DevAjMeireles\PagHiper;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class Request
{
    public const BASE_URL = 'https://api.paghiper.com/';

    public static function execute(string $endpoint, array $params = []): Response
    {
        $client = Http::timeout(10)
            ->baseUrl(config('paghiper.url') ?? self::BASE_URL)
            ->withHeaders([
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'application/json',
                'Content-Type'    => 'application/json',
            ]);

        $params = collect($params)->merge([
            'apiKey' => config('paghiper.api'),
            'token'  => config('paghiper.token'),
        ])->toArray();

        return $client->post($endpoint, $params);
    }

    public static function url(string $path): string
    {
        return (config('paghiper.url') ?? self::BASE_URL) . $path;
    }
}
