<?php
namespace CustomD\LaravelHelpers\Exceptions\Trait;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait DebuggableExceptionTrait
{

    /**
     *
     * @var string|array<int|string, mixed>|null
     */
    protected string|array|null $debugData = null;

    protected ?Request $request = null;


    public function setDebugData(string|array|null $debugData): self
    {
        $this->debugData = $debugData;
        return $this;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function getDebugData(): string|array|null
    {
        return $this->debugData;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * If your exception contains custom reporting logic that is only necessary when certain conditions are met,
     * you may need to instruct Laravel to sometimes report the exception using the default exception handling
     * configuration. To accomplish this, you may return false from the exception's report method:
     */
    public function report(): void
    {
        return Log::error($this->message, [
            'exception' => $this->getTraceAsString(),
            'request'   => $this->request,
            'debugData' => $this->debugData,
        ]);
    }



    protected function convertExceptionToArray(self $e): array
    {
        return config('app.debug') ? [
            'message'    => $e->getMessage(),
            'debug_data' => $e->getDebugData(),
            'exception'  => get_class($e),
            'previous'   => $e->getPrevious() ? [
                get_class($e->getPrevious()) => $e->getPrevious()->getMessage(),
                'trace'                      => array_slice($e->getPrevious()->getTrace(), 0, 3)
            ] : null,
            'file'       => $e->getFile(),
            'line'       => $e->getLine(),
            'trace'      => collect($e->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
        ] : [
            'message' => $e->getMessage(),
        ];
    }
}
