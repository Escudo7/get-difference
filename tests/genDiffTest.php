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
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
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
        $pathToFile1 = __DIR__ . "/testsFiles/flatBefore.json";
        $pathToFile2 = __DIR__ . "/testsFiles/flatAfter.json";
        $result = $this->prettyDiffFlat;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'pretty'));
    }

    public function testPrettyDiffNested()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/nestedBefore.yml";
        $pathToFile2 = __DIR__ . "/testsFiles/nestedAfter.yml";
        $result = $this->prettyDiffNested;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'pretty'));
    }

    public function testPlainDiffFlat()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/flatBefore.json";
        $pathToFile2 = __DIR__ . "/testsFiles/flatAfter.json";
        $result = $this->plainDiffFlat;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'plain'));
    }

    public function testPlainDiffNestes()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/nestedBefore.yml";
        $pathToFile2 = __DIR__ . "/testsFiles/nestedAfter.yml";
        $result = $this->plainDiffNested;
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'plain'));
    }
}
