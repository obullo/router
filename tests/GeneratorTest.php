<?php

use Obullo\Router\{
    Route,
    RequestContext,
    RouteCollection,
    Generator
};
use Obullo\Router\Types\{
    StrType,
    IntType,
    BoolType,
    SlugType,
    AnyType,
    FourDigitYearType,
    TwoDigitMonthType,
    TwoDigitDayType,
    TranslationType
};
class GeneratorTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $configArray = array(
            'types' => [
                new IntType('<int:id>'),
                new StrType('<str:name>'),
                new StrType('<str:word>'),
                new AnyType('<any:any>'),
                new BoolType('<bool:status>'),
                new IntType('<int:page>'),
                new SlugType('<slug:slug>'),
                new SlugType('<slug:slug_>', '(?<%s>[\w-_]+)'),
                new TranslationType('<locale:locale>'),
            ]
        );
        $request = Zend\Diactoros\ServerRequestFactory::fromGlobals();
        $this->config = new Zend\Config\Config($configArray);
        $this->context = new RequestContext;
        $this->context->fromRequest($request);

        $this->collection = new RouteCollection($this->config, $this->context);
        $this->collection->add('dummy', new Route('GET', '/<locale:locale>/dummy/<str:name>/<int:id>', 'App\Controller\DefaultController::dummy'));
        $this->collection->add('slug', new Route('GET', '/slug/<slug:slug_>', 'App\Controller\DefaultController::dummy'));
        $this->collection->add('test', new Route('GET', '/test/me', 'App\Controller\DefaultController::dummy'));
    }

    public function testGenerate()            
    {
        $dummy = (new Generator($this->collection))->generate('dummy', ['locale' => 'en', 'name' => 'test', 'id' => 5]);
        $slug  = (new Generator($this->collection))->generate('slug', ['slug_' => 'abcd-123_']);
        $test  = (new Generator($this->collection))->generate('test');

        $this->assertEquals($dummy, '/en/dummy/test/5');
        $this->assertEquals($slug, '/slug/abcd-123_');
        $this->assertEquals($test, '/test/me');
    }
}