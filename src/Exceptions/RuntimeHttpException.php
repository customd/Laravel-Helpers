<?php

namespace CustomD\LaravelHelpers\Exceptions;

use CustomD\LaravelHelpers\Exceptions\Trait\DebuggableExceptionTrait;
use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RuntimeHttpException extends RuntimeException implements HttpExceptionInterface
{
    use DebuggableExceptionTrait;

    private int $statusCode;

    private array $headers;

    public function __construct(int $statusCode, string $message = '', array|string|null $debugData = null, \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->setDebugData($debugData);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public static function err404(string $message = "Not Found", string|array|null $debugData = null): static
    {
        //@phpstan-ignore-next-line
        return new static(404, $message, $debugData);
    }

    public static function err500(string $message = "Internal Server Error", string|array|null $debugData = null): static
    {
        //@phpstan-ignore-next-line
        return new static(500, $message, $debugData);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json(
            $this->convertExceptionToArray($this),
            $this->getStatusCode(),
            $this->getHeaders(),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
