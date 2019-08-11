<?php

namespace Project\Tests;

use \PHPUnit\Framework\TestCase;
use function Project\Diff\getDiff;

class DiffTest extends TestCase
{
    public function setUp(): void
    {
        $this->beforeFlat = __DIR__ . "/testsFiles/flatBefore.json";
        $this->afterFlat = __DIR__ . "/testsFiles/flatAfter.json";
        $this->beforeNested = __DIR__ . "/testsFiles/nestedBefore.yml";
        $this->afterNested = __DIR__ . "/testsFiles/nestedAfter.yml";
    }
    
    public function testPrettyDiffFlat()
    {
        $pathToResult = __DIR__ . "/testsFiles/resultFlatPretty";
        $result = file_get_contents($pathToResult);

        $this->assertEquals($result, getDiff($this->beforeFlat, $this->afterFlat, 'pretty'));
    }

    public function testPrettyDiffNested()
    {
        $pathToResult = __DIR__ . "/testsFiles/resultNestedPretty";
        $result = file_get_contents($pathToResult);
        
        $this->assertEquals($result, getDiff($this->beforeNested, $this->afterNested, 'pretty'));
    }

    public function testPlainDiffFlat()
    {
        $pathToResult = __DIR__ . "/testsFiles/resultFlatPlain";
        $result = file_get_contents($pathToResult);

        $this->assertEquals($result, getDiff($this->beforeFlat, $this->afterFlat, 'plain'));
    }

    public function testPlainDiffNestes()
    {
        $pathToResult = __DIR__ . "/testsFiles/resultNestedPlain";
        $result = file_get_contents($pathToResult);

        $this->assertEquals($result, getDiff($this->beforeNested, $this->afterNested, 'plain'));
    }

    public function testJsonDiffFlat()
    {
        $pathToResult = __DIR__ . "/testsFiles/resultFlatJson";
        $result = file_get_contents($pathToResult);

        $this->assertEquals($result, getDiff($this->beforeFlat, $this->afterFlat, 'json'));
    }
}
