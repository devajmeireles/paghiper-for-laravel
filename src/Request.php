<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{ResolveToken, ResolverApi};
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class Request
{
    public const PAGHIPER_BILLET_BASE_URL = 'https://api.paghiper.com/';

    public static function execute(string $endpoint, array $params = []): Response
    {
        $api   = app(ResolverApi::class)->resolve();
        $token = app(ResolveToken::class)->resolve();
        $url   = app(ResolveBilletNotificationUrl::class)->resolve();

        if ($url && isset($params['notification_url'])) {
            $params['notification_url'] = $url;
        }

        $client = Http::timeout(10)
            ->baseUrl(self::PAGHIPER_BILLET_BASE_URL)
            ->withHeaders([
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'application/json',
                'Content-Type'    => 'application/json',
            ]);

        $params = collect($params)->merge([
            'apiKey' => $api ?? config('paghiper.api'),
            'token'  => $token ?? config('paghiper.token'),
        ])->toArray();

        return $client->post($endpoint, $params);
    }

    public static function url(string $path): string
    {
        return self::PAGHIPER_BILLET_BASE_URL . $path;
    }
}
