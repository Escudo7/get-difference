<?php

namespace Project\Tests;

use \PHPUnit\Framework\TestCase;
use function Project\Diff\getDiff;

class DiffTest extends TestCase
{
    protected function setUp(): void
    {
        $this->prettyDiffFlat = "{
    host: hexlet.io
  - timeout: 50
  + timeout: 20
  - proxy: 123.234.53.22
  + verbose: true
}\n";

        $this->prettyDiffNested = "{
    host: hexlet.io
    unmodifiedArray: {
        key1: value1
        key2: value2
    }
  - timeout: 50
  + timeout: 20
  - proxy: 123.234.53.22
    modifiedArray: {
      - big: bag
      + big: head
        cat: dog
      + newCat: {
            one: pers
            two: sfinks
        }
    }
  + verbose: true
  + newArray: {
        newKey1: value1
        newKey2: value2
    }
}\n";

        $this->plainDiffFlat = "Property 'timeout' was changed. From '50' to '20'
Property 'proxy' was removed
Property 'verbose' was added with value: 'true'\n";

        $this->plainDiffNested = "Property 'common.setting2' was removed
Property 'common.setting6' was removed
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: 'complex value'
Property 'group1.baz' was changed. From 'bas' to 'bars'
Property 'group2' was removed
Property 'group3' was added with value: 'complex value'\n";
    }

    public function testPrettyDiffFlat()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/beforePrettyFlat.json";
        $pathToFile2 = __DIR__ . "/testsFiles/afterPrettyFlat.json";
        $result = $this->prettyDiffFlat;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'pretty'));
    }

    public function testPrettyDiffNested()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/beforePrettyNested.json";
        $pathToFile2 = __DIR__ . "/testsFiles/afterPrettyNested.json";
        $result = $this->prettyDiffNested;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'pretty'));
    }

    public function testPlainDiffFlat()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/beforePlainFlat.yml";
        $pathToFile2 = __DIR__ . "/testsFiles/afterPlainFlat.yml";
        $result = $this->plainDiffFlat;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'plain'));
    }

    public function testPlainDiffNestes()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/beforePlainNested.yml";
        $pathToFile2 = __DIR__ . "/testsFiles/afterPlainNested.yml";
        $result = $this->plainDiffNested;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'plain'));
    }
}
