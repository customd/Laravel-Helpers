<?php

namespace CustomD\LaravelHelpers\Traits;

use Illuminate\Support\Facades\Http;

trait RecordsOrFakesHttpCalls
{

    protected $path = 'tests/stubs/';

    protected $record = false;


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
            $this->storeRecordings($name, $type);
        }
    }
}