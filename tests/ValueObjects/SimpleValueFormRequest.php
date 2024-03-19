<?php
namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use Illuminate\Foundation\Http\FormRequest;

class SimpleValueFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value'      => ['required', 'string'],
            'item_count' => ['required', 'int', "min:10"],

        ];
    }
}
