<?php

namespace CustomD\LaravelHelpers\Traits;

use Illuminate\Support\Facades\Http;

trait RecordsOrFakesHttpCalls
{
    protected string $path = 'tests/stubs/';

    protected bool $record = false;


    protected function storeHttpRecordings(string $name): void
    {
        $responses = $this->getRecordedResponses();
        $path = base_path($this->path);

        foreach ($responses as $i => $response) {
            $file = $path . $i . '_' . $name . '.json';
            $json = json_encode($response, JSON_PRETTY_PRINT);
            file_put_contents($file, $json);
        }
    }

    protected function processRecordedTest(string $name, callable $callback): void
    {
        if ($this->record === true) {
            Http::enableRecording();
        } else {
            if(app()->version() >= 9.12){\
                Http::preventStrayRequests();
            }
            $seq = Http::fakeSequence();
            $path = base_path($this->path);
            collect(glob($path . '*_' . $name . '.json'))->map(function ($responseFile) use ($seq) {
                $response = json_decode(file_get_contents($responseFile), true);
                $seq->push(
                    $response['body'],
                    $response['status'],
                    $response['headers'],
                );
            });
        }

        $callback();

        if ($this->record === true) {
            $this->storeHttpRecordings($name);
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
