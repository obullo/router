<?php

use PHPUnit\Framework\TestCase;
use Obullo\Router\Pattern;
use Obullo\Router\Types\StrType;
use Obullo\Router\Types\IntType;
use Obullo\Router\Types\BoolType;
use Obullo\Router\Types\SlugType;
use Obullo\Router\Types\AnyType;
use Obullo\Router\Types\FourDigitYearType;
use Obullo\Router\Types\TwoDigitMonthType;
use Obullo\Router\Types\TwoDigitDayType;
use Obullo\Router\Types\TranslationType;

class PatternTest extends TestCase
{
    public function testGetTypes()
    {
        $config = array(
            'types' => [
                new IntType('<int:id>'),   // \d+
                new StrType('<str:name>'), // \w+
                new StrType('<str:word>'), // \w+
                new AnyType('<any:any>'),
                new BoolType('<bool:status>'),
                new IntType('<int:page>'),
                new SlugType('<slug:slug>'),
                new TranslationType('<locale:locale>'),
            ]
        );
        $pattern = new Pattern($config);
        $types = $pattern->getPatternTypes();
        foreach ($types as $type) {
            $this->assertTrue(
                in_array(
                    $type->getPattern(),
                    ['<int:id>','<str:name>','<str:word>','<any:any>','<bool:status>','<int:page>','<slug:slug>','<locale:locale>']
                )
            );
        }
    }

    public function testAdd()
    {
        $pattern = new Pattern;
        $pattern->add(new IntType('<int:id>'));
        $types = $pattern->getTaggedTypes();
        $this->assertEquals('<int:id>', $types['id']->getPattern());
    }

    public function testFormat()
    {
        $config = array(
            'types' => [
                new IntType('<int:id>'),   // \d+
                new StrType('<str:name>'), // \w+
                new StrType('<str:word>'), // \w+
                new AnyType('<any:any>'),
                new BoolType('<bool:status>'),
                new SlugType('<slug:slug>'),
                new TranslationType('<locale:locale>'),
            ]
        );
        $pattern = new Pattern($config);
        $this->assertEquals('(?<id>\d+)', $pattern->format('<int:id>'));
        $this->assertEquals('(?<name>\w+)', $pattern->format('<str:name>'));
        $this->assertEquals('(?<word>\w+)', $pattern->format('<str:word>'));
        $this->assertEquals('(?<any>.*)', $pattern->format('<any:any>'));
        $this->assertEquals('(?<status>[0-1])', $pattern->format('<bool:status>'));
        $this->assertEquals('(?<slug>[a-zA-Z0-9_-]+)', $pattern->format('<slug:slug>'));
        $this->assertEquals('(?<locale>[a-z]{2})', $pattern->format('<locale:locale>'));
    }

    public function testValidateUnformattedPatterns()
    {
        $config = array(
            'types' => [
                new IntType('<int:id>'),   // \d+
                new StrType('<str:name>'), // \w+
                new StrType('<str:word>'), // \w+
                new AnyType('<any:any>'),
                new BoolType('<bool:status>'),
                new SlugType('<slug:slug>'),
                new TranslationType('<locale:locale>'),
            ]
        );
        $pattern = new Pattern($config);
        try {
            $pattern->validateUnformattedPatterns('/test/<slug:slug>/<test:test>');
        } catch (\Exception $e) {
            $this->assertEquals('The route type <test:test> you used is undefined.', $e->getMessage());
        }
    }
}
