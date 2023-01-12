<?php
namespace CustomD\LaravelHelpers\Facades;

use Illuminate\Support\Facades\Facade;

class CdCarbonDate extends Facade
{

     protected static function getFacadeAccessor()
    {
        return 'cd-carbon-date';
    }
}
