<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\Tests\Support;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A minimal PSR-18 client that returns a pre-configured response.
 * Used in tests to avoid making real HTTP requests.
 */
final class MockHttpClient implements ClientInterface
{
    public function __construct(
        private readonly int $statusCode,
        private readonly string $body,
    ) {}

    public static function respondWith(int $statusCode, mixed $body): self
    {
        return new self(
            statusCode: $statusCode,
            body: is_array($body) ? json_encode($body) : $body,
        );
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return new Response(
            status: $this->statusCode,
            body: $this->body,
        );
    }
}