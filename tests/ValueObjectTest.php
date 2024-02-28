<?php
namespace CustomD\LaravelHelpers\Tests;

use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\Tests\ValueObjects\SimpleValue;
use CustomD\LaravelHelpers\Tests\ValueObjects\ComplexValue;
use CustomD\LaravelHelpers\Tests\ValueObjects\SimpleValueFormRequest;

class ValueObjectTest extends TestCase
{

    public function test_it_can_create_a_value_object()
    {
        $value = SimpleValue::make('test', 10);
        $this->assertInstanceOf(SimpleValue::class, $value);

        $this->assertEquals('test', $value->value);
        $this->assertEquals(10, $value->count);
        $this->assertIsInt($value->count);
    }

    public function test_it_can_create_a_value_object_from_request()
    {
        $request = new SimpleValueFormRequest(query: ['value' => 'test','count' => '11','age' => 33]);

        $validator = app('validator')->make(['value' => 'test','count' => '11', 'age' => 33], $request->rules());

        $request->setValidator($validator);
        $request->validateResolved();

        $value = SimpleValue::fromRequest($request, false);
        $this->assertInstanceOf(SimpleValue::class, $value);

        $this->assertEquals('test', $value->value);
    }

    public function test_it_validates_when_constructed()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $data = ['value' => 'test', 'count' => 9];
        $value = SimpleValue::make(...$data);
    }

    public function test_it_can_be_passed_an_array()
    {
        $data = ['value' => 'test', 'count' => 11];
        $value = SimpleValue::make(...$data);
        $this->assertEquals('test', $value->value);
        $this->assertEquals(11, $value->count);
        $this->assertIsInt($value->count);
    }

    public function test_a_complex_value()
    {
        $data = [
            'value'        => 'test',
            'address'      => [
                'street' => '123 Fake St'
            ],
            'simpleValue'  => SimpleValue::make(
                'test',
                11
            ),
            'simpleValues' => collect()
        ];
        $value = ComplexValue::make(...$data);
        $this->assertEquals('test', $value->value);
        $this->assertEquals(11, $value->simpleValue->count);
        $this->assertEquals('123 Fake St', $value->address['street']);
    }

    public function test_a_complex_value_construct()
    {
        $data = [
            'value'         => 'test',
            'simpleValue'   => [
                'count' => '11',
                'value' => 'test',
            ],
            'address'       => [
                'street' => '123 Fake St'
            ],
            'constructable' => ['a' => 'b', 'c' => 'd', 'this' => 'is_array'],
            'simpleValues'  => [
                ['value' => 'test', 'count' => 11],
                ['value' => 'test2', 'count' => 13],
                ['value' => 'test3', 'count' => 15],
            ]
        ];
        $value = ComplexValue::make(...$data);
        $this->assertEquals('test', $value->value);
        $this->assertEquals(11, $value->simpleValue->count);
        $this->assertEquals('123 Fake St', $value->address['street']);
        $this->assertTrue($value->constructable instanceof \CustomD\LaravelHelpers\Tests\ValueObjects\Constructable);
        $this->assertTrue($value->simpleValues instanceof \Illuminate\Support\Collection);
        $this->assertSame(3, $value->simpleValues->count());
        $this->assertSame('test', $value->simpleValues->first()->value);
    }
}
