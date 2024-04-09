<?php
namespace CustomD\LaravelHelpers\Tests;

use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\Tests\ValueObjects\SimpleValue;
use CustomD\LaravelHelpers\Tests\ValueObjects\ComplexValue;
use CustomD\LaravelHelpers\Tests\ValueObjects\SimpleValueFormRequest;
use Illuminate\Http\Request;
use Phpsa\LaravelApiController\Http\Resources\ApiResource;

class ValueObjectTest extends TestCase
{

    public function test_it_can_create_a_value_object()
    {
        $value = SimpleValue::make('test', 10);
        $this->assertInstanceOf(SimpleValue::class, $value);

        $this->assertEquals('test', $value->value);
        $this->assertEquals(10, $value->itemCount);
        $this->assertIsInt($value->itemCount);
    }

    public function test_it_can_create_a_value_object_from_request()
    {
        $request = new SimpleValueFormRequest(query: ['value' => 'test','item_count' => '11','item_age' => 33]);

        $validator = app('validator')->make(['value' => 'test','item_count' => '11', 'item_age' => 33], $request->rules());

        $request->setValidator($validator);
        $request->validateResolved();

        $value = SimpleValue::fromRequest($request, false);
        $this->assertInstanceOf(SimpleValue::class, $value);

        $this->assertEquals('test', $value->value);
    }

    public function test_it_validates_when_constructed()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $data = ['value' => 'test', 'itemCount' => 9];
        $value = SimpleValue::make(...$data);
    }

    public function test_it_can_be_passed_an_array()
    {
        $data = ['value' => 'test', 'itemCount' => 11];
        $value = SimpleValue::make(...$data);
        $this->assertEquals('test', $value->value);
        $this->assertEquals(11, $value->itemCount);
        $this->assertIsInt($value->itemCount);
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
        $this->assertEquals(11, $value->simpleValue->itemCount);
        $this->assertEquals('123 Fake St', $value->address['street']);
    }

    public function test_a_complex_value_construct()
    {
        $data = [
            'value'         => 'test',
            'simpleValue'   => [
                'itemCount' => '11',
                'value'     => 'test',
            ],
            'address'       => [
                'street' => '123 Fake St'
            ],
            'constructable' => ['a' => 'b', 'c' => 'd', 'this' => 'is_array'],
            'simpleValues'  => [
                ['value' => 'test', 'itemCount' => 11],
                ['value' => 'test2', 'itemCount' => 13],
                ['value' => 'test3', 'itemCount' => 15],
            ]
        ];
        $value = ComplexValue::make(...$data);
        $this->assertEquals('test', $value->value);
        $this->assertEquals(11, $value->simpleValue->itemCount);
        $this->assertEquals('123 Fake St', $value->address['street']);
        $this->assertTrue($value->constructable instanceof \CustomD\LaravelHelpers\Tests\ValueObjects\Constructable);
        $this->assertTrue($value->simpleValues instanceof \Illuminate\Support\Collection);
        $this->assertSame(3, $value->simpleValues->count());
        $this->assertSame('test', $value->simpleValues->first()->value);
        $this->assertTrue($value->simpleValues->first() instanceof \CustomD\LaravelHelpers\Tests\ValueObjects\SimpleValue);
    }

    public function testing_standard_resource_object()
    {
        $value = SimpleValue::make('test', 10);
        $this->assertInstanceOf(SimpleValue::class, $value);

        $this->assertSame(
            json_encode(['data' => $value->toArray()]),
            $value->toJsonResource()->toResponse(new Request())->content()
        );
    }

    public function test_a_complex_value_construct_with_api_resource()
    {
        $data = [
            'value'         => 'test',
            'simpleValue'   => [
                'itemCount' => '11',
                'value'     => 'test',
            ],
            'address'       => [
                'street' => '123 Fake St'
            ],
            'constructable' => ['a' => 'b', 'c' => 'd', 'this' => 'is_array'],
            'simpleValues'  => [
                ['value' => 'test', 'itemCount' => 11],
                ['value' => 'test2', 'itemCount' => 13],
                ['value' => 'test3', 'itemCount' => 15],
            ]
        ];
        $value = ComplexValue::make(...$data);

        $res = json_decode($value->toJsonResource(ApiResource::class)->toResponse(new Request())->content(), true);
        $this->assertIsArray($res['data']);
    }
}
