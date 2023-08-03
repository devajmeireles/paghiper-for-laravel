<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Exceptions\UnallowedEmptyNotificationUrl;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{Pix\ResolvePixNotificationUrl, ResolveToken, ResolverApi};
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class Request
{
    public const PAGHIPER_BASE_URL = 'https://{resource}.paghiper.com/';

    protected static string $base;

    protected static string $resource;

    /** @throws RuntimeException */
    public static function resource(string $resource): self
    {
        $resource = $resource === 'billet' ? 'api' : $resource;

        if (!in_array($resource, ['api', 'pix'])) {
            throw new RuntimeException("Invalid resource type: [$resource]");
        }

        $class            = new self();
        $class::$base     = str(self::PAGHIPER_BASE_URL)->replace('{resource}', $resource)->value();
        $class::$resource = $resource;

        return $class;
    }

    /** @throws UnallowedEmptyNotificationUrl */
    public static function execute(string $endpoint, array $params = []): Response
    {
        $api   = app(ResolverApi::class)->resolve();
        $token = app(ResolveToken::class)->resolve();
        $url   = self::$resource === 'api'
            ? app(ResolveBilletNotificationUrl::class)->resolve()
            : app(ResolvePixNotificationUrl::class)->resolve();

        if ($url) {
            $params['notification_url'] = $url;
        }

        if (str($endpoint)->contains('create/') && empty(data_get($params, 'notification_url'))) {
            throw new UnallowedEmptyNotificationUrl();
        }

        $client = Http::timeout(10)
            ->baseUrl(self::$base)
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
        return self::$base . $path;
    }
}
