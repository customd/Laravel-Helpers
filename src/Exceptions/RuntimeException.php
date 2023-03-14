<?php
namespace CustomD\LaravelHelpers\Exceptions;

use Illuminate\Http\JsonResponse;
use Throwable;
use RuntimeException as CoreException;

class RuntimeException extends CoreException
{
    public int $statusCode = 500;

    public string $extra = '';


    public function __construct(string $message = "", string $extra = '', int $statusCode = 500, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->extra = $extra;
        $this->statusCode = $statusCode;
    }


    public static function err404(string $message = "Not Found", string $extra = ''): static
    {
        //@phpstan-ignore-next-line
        return new static($message, $extra, 404);
    }


    public static function err500(string $message = "Internal Server Error", string $extra = ''): static
    {
        //@phpstan-ignore-next-line
        return new static($message, $extra, 500);
    }

    public function render(): JsonResponse
    {
        return new JsonResponse([
            'message' => $this->message,
            'extra'   => $this->extra,
            'type'    => __CLASS__,
        ], $this->statusCode);
    }
}
