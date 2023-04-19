<?php

namespace CustomD\LaravelHelpers\Traits;

use Illuminate\Support\Facades\Http;

trait RecordsOrFakesHttpCalls
{
    protected string $path = 'tests/stubs/';

    protected bool $record = false;


    protected function storeHttpRecordings(string $name, $type = 'html'): void
    {
        $recordings = Http::recorded();
        $path = base_path($this->path);

        foreach ($recordings as $i => $recording) {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = $recording[1];
            $file = $path . $i . '_' . $name . '.' . $type;
            file_put_contents($file, $response->body());
        }
    }

    protected function processRecordedTest(string $name, callable $callback, string $type = 'html'): void
    {
        if ($this->record === true) {
            Http::enableRecording();
        } else {
            Http::preventStrayRequests();
            $seq = Http::fakeSequence();
            $path = base_path($this->path);
            collect(glob($path . '*_' . $name . '.' . $type))->map(fn($call) =>  $seq->push(file_get_contents($call), 200));
        }

        $callback();

        if ($this->record === true) {
            $this->storeHttpRecordings($name, $type);
        }
    }

    protected function getRecordings($idx = null, $raw = false)
    {
        $items =  Http::recorded()->map(function ($recording) use ($raw) {
            /** @var \Illuminate\Http\Client\Request $request*/
            $request = $recording[0];
            /** @var \Illuminate\Http\Client\Response $response */
            $response = $recording[1];
            if ($raw === true) {
                return [
                    'request'  => $request,
                    'response' => $response
                ];
            }
            return [
                'request'  => [
                    'headers' => $request->headers(),
                    'body'    => $request->body(),
                    'method'  => $request->method(),
                    'url'     => $request->url(),
                ],
                'response' => [
                    'body'    => $response->body(),
                    'status'  => $response->status(),
                    'headers' => $response->headers(),
                ],
            ];
        });

        return $idx ? $items->get(--$idx) : $items;
    }

    protected function getRecordedRequests($idx = null)
    {

        $recordings = $this->getRecordings()->map(fn ($recording) => $recording['request']);

        return $idx ? $recordings->get(--$idx) : $recordings;
    }

    protected function getRecordedResponses($idx = null)
    {
        $recordings = $this->getRecordings()->map(fn ($recording) => $recording['response']);

        return $idx ? $recordings->get(--$idx) : $recordings;
    }
}