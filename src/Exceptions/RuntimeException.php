<?php
namespace CustomD\LaravelHelpers\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use RuntimeException as CoreException;
use CustomD\LaravelHelpers\Exceptions\Trait\DebuggableExceptionTrait;

class RuntimeException extends CoreException
{
    use DebuggableExceptionTrait;


    public function __construct(string $message = '', array|string|null $debugData = null, \Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->setDebugData($debugData);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json(
            $this->convertExceptionToArray($this),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
